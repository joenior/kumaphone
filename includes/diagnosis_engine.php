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
        "SELECT * FROM gejala WHERE jenishp = ?",
        [$operatingSystem]
    );
}

/**
 * Run Forward Chaining: given a list of selected symptoms,
 * find all matching damage records from the knowledge base.
 *
 * Returns an array of unique damage records, each with:
 *   idkerusakan, namakerusakan, jenishp
 */
function runForwardChaining(PDO $db, array $selectedSymptoms): array
{
    if (empty($selectedSymptoms)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($selectedSymptoms), '?'));

    $sql = "SELECT DISTINCT p.idkerusakan, p.namakerusakan, p.jenishp
            FROM basispengetahuan b
            JOIN kerusakan p ON p.namakerusakan = b.namakerusakan
            WHERE b.gejala IN ({$placeholders})
            GROUP BY p.idkerusakan, p.namakerusakan, p.jenishp";

    return fetchAll($db, $sql, $selectedSymptoms);
}

/**
 * Save a customer's diagnosis record to the smartphones table.
 */
function saveDiagnosisRecord(
    PDO    $db,
    string $customerName,
    string $brandModel,
    string $imei,
    string $operatingSystem,
    string $damageId
): bool {
    $sql = "INSERT INTO smartphones (brand, model, imei, jenishp, idkerusakan)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $db->prepare($sql);
    return $stmt->execute([$customerName, $brandModel, $imei, $operatingSystem, $damageId]);
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
