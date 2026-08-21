<?php
/**
 * Lembar Cetak & Ekspor PDF Laporan Hasil Diagnosa
 * Kumaphone - Sistem Pakar Diagnosa Kerusakan Smartphone
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';
require_once 'includes/diagnosis_engine.php';

$recordId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$regCode  = trim($_GET['reg'] ?? '');

$record = null;
if (!empty($regCode)) {
    $record = fetchDiagnosisRecordByRegistration($db, $regCode);
} elseif ($recordId > 0) {
    $record = fetchDiagnosisRecordById($db, $recordId);
}

if (!$record) {
    header('Location: tracking.php');
    exit;
}

$damageId = $record['idkerusakan'] ?? '';
$symptoms = !empty($damageId) ? fetchSymptomsForDamage($db, $damageId) : [];

// Gunakan nomor registrasi permanen yang tersimpan di database
$docNumber = $record['no_registrasi'] ?? ('KMP-' . date('Ymd') . '-' . str_pad((string)$record['id'], 4, '0', STR_PAD_LEFT));
$printDate = !empty($record['created_at']) ? date('d F Y, H:i', strtotime($record['created_at'])) . ' WIB' : date('d F Y');
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Diagnosa - <?= escape($record['model']) ?> (<?= escape($docNumber) ?>)</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- html2pdf.js for client-side instant PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        :root {
            --ink: #0d1b2a;
            --paper: #ffffff;
            --muted: #576574;
            --line: #d1d8e0;
            --green: #2f735b;
            --green-light: #e8f5e9;
            --font-sans: 'Manrope', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-mono: 'DM Mono', monospace;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            color: var(--ink);
            font-family: var(--font-sans);
            font-size: 13px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Top Action Bar (Screen Only) */
        .no-print-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #0d1b2a;
            color: #ffffff;
            padding: 0.9rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .bar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .bar-badge {
            background: #b6cc78;
            color: #0d1b2a;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-weight: 700;
        }

        .bar-actions {
            display: flex;
            gap: 0.6rem;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            font-family: var(--font-sans);
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-print {
            background: #2f735b;
            color: #ffffff;
        }

        .btn-print:hover {
            background: #245a47;
        }

        .btn-pdf {
            background: #b6cc78;
            color: #0d1b2a;
        }

        .btn-pdf:hover {
            background: #a4ba64;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Document Container */
        .page-container {
            max-width: 800px;
            margin: 2rem auto 4rem;
            padding: 0 1rem;
        }

        .report-sheet {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 2.5rem 3rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        /* Header / Kop Surat */
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--ink);
            padding-bottom: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 6px;
        }

        .brand-text h1 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--ink);
            line-height: 1.2;
        }

        .brand-text p {
            margin: 0.15rem 0 0;
            font-size: 0.75rem;
            color: var(--muted);
            font-weight: 500;
        }

        .doc-meta {
            text-align: right;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--muted);
        }

        .doc-meta strong {
            display: block;
            font-size: 0.85rem;
            color: var(--ink);
            margin-bottom: 0.2rem;
        }

        /* Section Headings */
        .doc-title-bar {
            text-align: center;
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .doc-title-bar h2 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ink);
        }

        .doc-title-bar p {
            margin: 0.2rem 0 0;
            font-size: 0.72rem;
            color: var(--muted);
        }

        /* Data Tables / Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .info-card {
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 1rem 1.15rem;
            background: #ffffff;
        }

        .card-header-label {
            font-family: var(--font-mono);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--green);
            margin-bottom: 0.75rem;
            display: block;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 0.35rem;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 0.3rem 0;
            font-size: 0.82rem;
            vertical-align: top;
        }

        .info-table td.label {
            width: 42%;
            color: var(--muted);
            font-weight: 500;
        }

        .info-table td.value {
            font-weight: 700;
            color: var(--ink);
        }

        /* Diagnosis Conclusion Box */
        .diagnosis-box {
            background: #f4fbf7;
            border: 1.5px solid #2f735b;
            border-radius: 6px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .diagnosis-box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.65rem;
        }

        .diagnosis-badge {
            background: #2f735b;
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            font-family: var(--font-mono);
            padding: 0.25rem 0.6rem;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .damage-name {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 800;
            color: #1a4335;
        }

        .damage-id-pill {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: #2f735b;
            font-weight: 600;
        }

        /* Symptoms List */
        .symptoms-box {
            margin-bottom: 1.5rem;
        }

        .symptoms-title {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--ink);
            margin-bottom: 0.5rem;
        }

        .symptom-list-print {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.4rem 1rem;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .symptom-list-print li {
            font-size: 0.78rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: #2d3748;
            background: #f8fafc;
            padding: 0.35rem 0.6rem;
            border: 1px solid #edf2f7;
            border-radius: 4px;
        }

        .symptom-list-print li span.icon {
            color: var(--green);
            font-weight: bold;
        }

        /* Repair Steps Box */
        .repair-box {
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 1.15rem 1.25rem;
            background: #ffffff;
            margin-bottom: 1.75rem;
        }

        .repair-title {
            font-family: var(--font-mono);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--green);
            margin-bottom: 0.6rem;
        }

        .repair-content {
            font-size: 0.82rem;
            line-height: 1.6;
            color: #2d3748;
            white-space: pre-line;
            background: #fafbfc;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            border-left: 3px solid var(--green);
        }

        /* Signature Section */
        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px dashed var(--line);
        }

        .sig-box {
            text-align: center;
        }

        .sig-box p.role {
            font-size: 0.75rem;
            color: var(--muted);
            margin: 0 0 4rem;
            font-weight: 600;
        }

        .sig-line {
            border-bottom: 1px solid var(--ink);
            width: 80%;
            margin: 0 auto 0.35rem;
        }

        .sig-name {
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--ink);
        }

        /* Footer Notes */
        .report-footer-notes {
            margin-top: 1.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            font-size: 0.68rem;
            color: #a0aec0;
            font-family: var(--font-mono);
        }

        /* Print Media Styles */
        @media print {
            body {
                background: #ffffff !important;
                font-size: 11.5px;
            }

            .no-print-bar {
                display: none !important;
            }

            .page-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .report-sheet {
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }

            @page {
                size: A4 portrait;
                margin: 12mm 15mm;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (Hanya tampil di layar) -->
    <div class="no-print-bar">
        <div class="bar-left">
            <span>Lembar Laporan Diagnosa</span>
            <span class="bar-badge"><?= escape($docNumber) ?></span>
        </div>
        <div class="bar-actions">
            <button type="button" class="btn-action btn-pdf" onclick="exportToPDF()">
                Unduh PDF (.pdf)
            </button>
            <button type="button" class="btn-action btn-print" onclick="window.print()">
                Cetak Laporan (Print)
            </button>
            <a href="diagnosa.php" class="btn-action btn-back">
                Kembali
            </a>
        </div>
    </div>

    <div class="page-container">
        <div class="report-sheet" id="report-content">
            
            <!-- Header / Kop Dokumen -->
            <header class="report-header">
                <div class="brand-box">
                    <img class="brand-logo" src="img/logo.png" alt="Kumaphone Logo">
                    <div class="brand-text">
                        <h1>KUMAPHONE SERVICE LAB</h1>
                        <p>Sistem Pakar Diagnosa Kerusakan Smartphone (Forward Chaining)</p>
                    </div>
                </div>
                <div class="doc-meta">
                    <strong><?= escape($docNumber) ?></strong>
                    <span>Tanggal: <?= escape($printDate) ?></span>
                </div>
            </header>

            <!-- Judul Laporan -->
            <div class="doc-title-bar">
                <h2>Lembar Hasil Pemeriksaan Teknis</h2>
                <p>Dokumen resmi hasil evaluasi inferensi kerusakan smartphone</p>
            </div>

            <!-- Informasi Pelanggan & Perangkat -->
            <div class="info-grid">
                <div class="info-card">
                    <span class="card-header-label">Data Pelanggan</span>
                    <table class="info-table">
                        <tr>
                            <td class="label">Nama Pemilik:</td>
                            <td class="value"><?= escape($record['brand']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">No. Registrasi:</td>
                            <td class="value">#<?= str_pad((string)$record['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Status:</td>
                            <td class="value" style="color: #2f735b;">Selesai Didiagnosa</td>
                        </tr>
                    </table>
                </div>

                <div class="info-card">
                    <span class="card-header-label">Spesifikasi Perangkat</span>
                    <table class="info-table">
                        <tr>
                            <td class="label">Merek &amp; Model:</td>
                            <td class="value"><?= escape($record['model']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Nomor IMEI:</td>
                            <td class="value" style="font-family: var(--font-mono);"><?= escape($record['imei']) ?></td>
                        </tr>
                        <tr>
                            <td class="label">Sistem Operasi:</td>
                            <td class="value"><?= escape($record['jenishp']) ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Kesimpulan Kerusakan Utama -->
            <div class="diagnosis-box">
                <div class="diagnosis-box-header">
                    <span class="diagnosis-badge">Hasil Inferensi Utama</span>
                    <span class="damage-id-pill">Kode Kerusakan: <?= escape($record['idkerusakan']) ?></span>
                </div>
                <h3 class="damage-name"><?= escape($record['namakerusakan'] ?? 'Kerusakan Tidak Diketahui') ?></h3>
            </div>

            <!-- Gejala Syarat Aturan Terkait -->
            <?php if (!empty($symptoms)): ?>
            <div class="symptoms-box">
                <div class="symptoms-title">Gejala Karakteristik (Basis Pengetahuan Aturan):</div>
                <ul class="symptom-list-print">
                    <?php foreach ($symptoms as $sym): ?>
                        <li>
                            <span class="icon">✓</span>
                            <span><?= escape($sym['gejala']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <!-- Arahan Perbaikan / Solusi -->
            <div class="repair-box">
                <div class="repair-title">Rekomendasi Tindakan &amp; Arahan Penanganan:</div>
                <div class="repair-content">
                    <?= escape($record['caramengatasi'] ?? 'Belum ada arahan penanganan khusus yang terdaftar.') ?>
                </div>
            </div>

            <!-- Lembar Pengesahan / Tanda Tangan -->
            <div class="signature-grid">
                <div class="sig-box">
                    <p class="role">Pelanggan / Pemilik Perangkat</p>
                    <div class="sig-line"></div>
                    <span class="sig-name">( <?= escape($record['brand']) ?> )</span>
                </div>
                <div class="sig-box">
                    <p class="role">Teknisi / Pemeriksa Lab</p>
                    <div class="sig-line"></div>
                    <span class="sig-name">( Tim Teknisi Kumaphone )</span>
                </div>
            </div>

            <!-- Footer Catatan -->
            <div class="report-footer-notes">
                <span>Dicetak otomatis oleh Kumaphone Expert System Engine</span>
                <span>Halaman 1 dari 1</span>
            </div>

        </div>
    </div>

    <script>
        function exportToPDF() {
            const element = document.getElementById('report-content');
            const modelName = '<?= addslashes(preg_replace('/[^a-zA-Z0-9_-]/', '_', $record['model'])) ?>';
            const imei = '<?= addslashes($record['imei']) ?>';
            const fileName = `Laporan-Diagnosa-${modelName}-${imei}.pdf`;

            const opt = {
                margin:       [10, 10, 10, 10],
                filename:     fileName,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Jalankan html2pdf
            html2pdf().set(opt).from(element).save();
        }

        // Auto print jika ada query parameter autoprint=1
        <?php if (isset($_GET['autoprint']) && $_GET['autoprint'] === '1'): ?>
        window.addEventListener('load', () => {
            setTimeout(() => { window.print(); }, 400);
        });
        <?php endif; ?>
    </script>
</body>
</html>
