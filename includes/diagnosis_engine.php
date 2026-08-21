<?php
/**
 * Forward Chaining Diagnosis Engine.
 *
 * Encapsulates the expert system logic for diagnosing smartphone damage
 * based on selected symptoms (gejala) against the knowledge base
 * (basispengetahuan).
 */

/**
 * Fetch all symptoms for a given operating system.
 *
 * @return array List of symptom rows with keys: idgejala, gejala, jenishp
 */
function fetchSymptomsByOS(PDO $db, string $operatingSystem): array
{
    return fetchAll(
        $db,
        "SELECT * FROM gejala WHERE jenishp = ? ORDER BY idgejala ASC",
        [$operatingSystem]
    );
}

/**
 * Run Forward Chaining: given a list of selected symptoms,
 * evaluates all rules in the knowledge base, calculates the match score
 * for each potential damage type, and returns ranked conclusions.
 *
 * @param PDO $db
 * @param array $selectedSymptoms List of string symptoms chosen by the user
 * @return array Ranked array of diagnosis results with coverage metrics
 */
function runForwardChaining(PDO $db, array $selectedSymptoms): array
{
    if (empty($selectedSymptoms)) {
        return [];
    }

    // 1. Fetch all damage types and their complete rule premises from knowledge base
    $allRules = fetchAll(
        $db,
        "SELECT k.idkerusakan, k.namakerusakan, k.jenishp, k.caramengatasi, b.gejala
         FROM kerusakan k
         JOIN basispengetahuan b ON k.namakerusakan = b.namakerusakan
         ORDER BY k.idkerusakan ASC"
    );

    if (empty($allRules)) {
        return [];
    }

    // 2. Group premises by damage ID
    $damageGroups = [];
    foreach ($allRules as $rule) {
        $id = $rule['idkerusakan'];
        if (!isset($damageGroups[$id])) {
            $damageGroups[$id] = [
                'idkerusakan'   => $rule['idkerusakan'],
                'namakerusakan' => $rule['namakerusakan'],
                'jenishp'       => $rule['jenishp'],
                'caramengatasi' => $rule['caramengatasi'],
                'all_symptoms'  => [],
            ];
        }
        $damageGroups[$id]['all_symptoms'][] = $rule['gejala'];
    }

    // 3. Evaluate each damage type against user's selected symptoms
    $results = [];
    $selectedMap = array_fill_keys($selectedSymptoms, true);

    foreach ($damageGroups as $id => $item) {
        $allSymptoms = $item['all_symptoms'];
        $totalRules = count($allSymptoms);
        
        $matched = [];
        $unmatched = [];

        foreach ($allSymptoms as $symptom) {
            if (isset($selectedMap[$symptom])) {
                $matched[] = $symptom;
            } else {
                $unmatched[] = $symptom;
            }
        }

        $matchedCount = count($matched);

        // Only include if at least 1 symptom matches
        if ($matchedCount > 0) {
            $percentage = $totalRules > 0 ? (int)round(($matchedCount / $totalRules) * 100) : 0;
            $isExact = ($matchedCount === $totalRules);

            $results[] = [
                'idkerusakan'        => $item['idkerusakan'],
                'namakerusakan'      => $item['namakerusakan'],
                'jenishp'            => $item['jenishp'],
                'caramengatasi'      => $item['caramengatasi'],
                'total_rules'        => $totalRules,
                'matched_count'      => $matchedCount,
                'matched_symptoms'   => $matched,
                'unmatched_symptoms' => $unmatched,
                'match_percentage'   => $percentage,
                'is_exact_match'     => $isExact,
            ];
        }
    }

    // 4. Conflict Resolution / Ranking:
    // Sort primarily by match_percentage DESC, then matched_count DESC, then idkerusakan ASC
    usort($results, function ($a, $b) {
        if ($b['match_percentage'] !== $a['match_percentage']) {
            return $b['match_percentage'] <=> $a['match_percentage'];
        }
        if ($b['matched_count'] !== $a['matched_count']) {
            return $b['matched_count'] <=> $a['matched_count'];
        }
        return strcmp($a['idkerusakan'], $b['idkerusakan']);
    });

    return $results;
}

/**
 * Ensure database schema has no_registrasi and created_at columns in smartphones table.
 */
function ensureSmartphoneSchema(PDO $db): void
{
    static $checked = false;
    if ($checked) {
        return;
    }

    try {
        // Check if no_registrasi column exists
        $columns = fetchAll($db, "SHOW COLUMNS FROM smartphones LIKE 'no_registrasi'");
        if (empty($columns)) {
            $db->exec("ALTER TABLE smartphones ADD COLUMN no_registrasi VARCHAR(30) NULL AFTER id");
        }

        // Check if created_at column exists
        $createdAtCol = fetchAll($db, "SHOW COLUMNS FROM smartphones LIKE 'created_at'");
        if (empty($createdAtCol)) {
            $db->exec("ALTER TABLE smartphones ADD COLUMN created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER idkerusakan");
        }

        // Backfill any empty no_registrasi
        $emptyRows = fetchAll($db, "SELECT id, created_at FROM smartphones WHERE no_registrasi IS NULL OR no_registrasi = ''");
        if (!empty($emptyRows)) {
            $updateStmt = $db->prepare("UPDATE smartphones SET no_registrasi = ? WHERE id = ?");
            foreach ($emptyRows as $row) {
                $datePart = !empty($row['created_at']) ? date('Ymd', strtotime($row['created_at'])) : date('Ymd');
                $code = 'KMP-' . $datePart . '-' . str_pad((string)$row['id'], 4, '0', STR_PAD_LEFT);
                $updateStmt->execute([$code, $row['id']]);
            }
        }
    } catch (PDOException $e) {
        error_log("Schema auto-migration notice: " . $e->getMessage());
    }

    $checked = true;
}

/**
 * Generate a unique registration code in the format KMP-YYYYMMDD-XXXX.
 */
function generateRegistrationCode(PDO $db): string
{
    ensureSmartphoneSchema($db);

    $datePrefix = 'KMP-' . date('Ymd') . '-';
    
    // Count existing records today
    $todayRecords = fetchOne(
        $db,
        "SELECT COUNT(*) as count FROM smartphones WHERE no_registrasi LIKE ?",
        [$datePrefix . '%']
    );

    $sequence = (int)($todayRecords['count'] ?? 0) + 1;
    $code = $datePrefix . str_pad((string)$sequence, 4, '0', STR_PAD_LEFT);

    // Ensure strictly unique
    $exists = fetchOne($db, "SELECT id FROM smartphones WHERE no_registrasi = ?", [$code]);
    while ($exists) {
        $sequence++;
        $code = $datePrefix . str_pad((string)$sequence, 4, '0', STR_PAD_LEFT);
        $exists = fetchOne($db, "SELECT id FROM smartphones WHERE no_registrasi = ?", [$code]);
    }

    return $code;
}

/**
 * Save a customer's diagnosis record to the smartphones table with permanent registration number.
 *
 * @return array|false Returns associative array ['id' => int, 'no_registrasi' => string] on success, false on failure
 */
function saveDiagnosisRecord(
    PDO    $db,
    string $customerName,
    string $brandModel,
    string $imei,
    string $operatingSystem,
    string $damageId
): array|false {
    ensureSmartphoneSchema($db);

    $regCode = generateRegistrationCode($db);

    $sql = "INSERT INTO smartphones (no_registrasi, brand, model, imei, jenishp, idkerusakan, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $db->prepare($sql);
    $success = $stmt->execute([$regCode, $customerName, $brandModel, $imei, $operatingSystem, $damageId]);
    
    if ($success) {
        return [
            'id'            => (int) $db->lastInsertId(),
            'no_registrasi' => $regCode,
        ];
    }

    return false;
}

/**
 * Fetch a complete diagnosis record by smartphone ID joined with damage details.
 */
function fetchDiagnosisRecordById(PDO $db, int $id): ?array
{
    ensureSmartphoneSchema($db);

    return fetchOne(
        $db,
        "SELECT s.*, k.namakerusakan, k.caramengatasi
         FROM smartphones s
         LEFT JOIN kerusakan k ON s.idkerusakan = k.idkerusakan
         WHERE s.id = ?",
        [$id]
    );
}

/**
 * Fetch a complete diagnosis record by registration number.
 */
function fetchDiagnosisRecordByRegistration(PDO $db, string $noRegistrasi): ?array
{
    ensureSmartphoneSchema($db);

    return fetchOne(
        $db,
        "SELECT s.*, k.namakerusakan, k.caramengatasi
         FROM smartphones s
         LEFT JOIN kerusakan k ON s.idkerusakan = k.idkerusakan
         WHERE s.no_registrasi = ?",
        [$noRegistrasi]
    );
}

/**
 * Search diagnosis records by Registration Code or IMEI for public tracking.
 */
function searchDiagnosisForTracking(PDO $db, string $keyword): array
{
    ensureSmartphoneSchema($db);

    $keyword = trim($keyword);
    if ($keyword === '') {
        return [];
    }

    return fetchAll(
        $db,
        "SELECT s.*, k.namakerusakan, k.caramengatasi
         FROM smartphones s
         LEFT JOIN kerusakan k ON s.idkerusakan = k.idkerusakan
         WHERE s.no_registrasi = ? 
            OR s.imei = ? 
            OR s.imei LIKE ?
            OR s.model LIKE ?
         ORDER BY s.id DESC",
        [$keyword, $keyword, "%{$keyword}%", "%{$keyword}%"]
    );
}

/**
 * Fetch a damage record by its ID, including the solution.
 */
function fetchDamageById(PDO $db, string $damageId): ?array
{
    return fetchOne(
        $db,
        "SELECT * FROM kerusakan WHERE idkerusakan = ?",
        [$damageId]
    );
}

/**
 * Fetch all symptoms associated with a damage record (via knowledge base).
 */
function fetchSymptomsForDamage(PDO $db, string $damageId): array
{
    return fetchAll(
        $db,
        "SELECT b.gejala
         FROM kerusakan p
         JOIN basispengetahuan b ON p.namakerusakan = b.namakerusakan
         WHERE p.idkerusakan = ?",
        [$damageId]
    );
}

