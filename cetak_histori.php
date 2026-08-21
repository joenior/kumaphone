<?php
/**
 * Lembar Cetak & Ekspor PDF Rekapitulasi Histori Diagnosa
 * Kumaphone Admin
 */
require_once 'session.php';
requireAdmin();

$historyList = fetchAll(
    $db,
    "SELECT s.*, k.namakerusakan
     FROM smartphones s
     LEFT JOIN kerusakan k ON s.idkerusakan = k.idkerusakan
     ORDER BY s.id DESC"
);

$printDate = date('d F Y, H:i');
$totalCount = count($historyList);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekapitulasi Histori Diagnosa - Kumaphone</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        :root {
            --ink: #0d1b2a;
            --muted: #576574;
            --line: #d1d8e0;
            --green: #2f735b;
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
            font-size: 12px;
            line-height: 1.4;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #0d1b2a;
            color: #ffffff;
            padding: 0.85rem 1.5rem;
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
            padding: 0.45rem 0.9rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
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

        .page-container {
            max-width: 960px;
            margin: 2rem auto 4rem;
            padding: 0 1rem;
        }

        .report-sheet {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 2.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--ink);
            padding-bottom: 1rem;
            margin-bottom: 1.25rem;
        }

        .brand-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
        }

        .brand-text h1 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--ink);
        }

        .brand-text p {
            margin: 0.1rem 0 0;
            font-size: 0.72rem;
            color: var(--muted);
        }

        .doc-meta {
            text-align: right;
            font-family: var(--font-mono);
            font-size: 0.72rem;
            color: var(--muted);
        }

        .doc-meta strong {
            display: block;
            font-size: 0.82rem;
            color: var(--ink);
        }

        .doc-title-bar {
            text-align: center;
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 6px;
            padding: 0.6rem;
            margin-bottom: 1.25rem;
        }

        .doc-title-bar h2 {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        .table-custom th {
            background: #f1f5f9;
            color: var(--ink);
            font-family: var(--font-mono);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.6rem 0.5rem;
            border: 1px solid var(--line);
            text-align: left;
        }

        .table-custom td {
            padding: 0.55rem 0.5rem;
            border: 1px solid var(--line);
            font-size: 0.76rem;
            vertical-align: middle;
        }

        .table-custom tr:nth-child(even) td {
            background: #fafbfc;
        }

        .imei-code {
            font-family: var(--font-mono);
            font-size: 0.72rem;
        }

        .damage-tag {
            display: inline-block;
            font-family: var(--font-mono);
            font-size: 0.68rem;
            padding: 0.15rem 0.4rem;
            background: #e8f5e9;
            color: #2f735b;
            border-radius: 3px;
            font-weight: 700;
        }

        .report-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--muted);
            border-top: 1px solid var(--line);
            padding-top: 0.75rem;
        }

        @media print {
            body {
                background: #ffffff !important;
                font-size: 10.5px;
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
                size: A4 landscape;
                margin: 12mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <div class="bar-left">
            <span>Rekapitulasi Histori Diagnosa</span>
            <span class="bar-badge"><?= $totalCount ?> Data Tercatat</span>
        </div>
        <div class="bar-actions">
            <button type="button" class="btn-action btn-pdf" onclick="exportToPDF()">
                Unduh PDF (.pdf)
            </button>
            <button type="button" class="btn-action btn-print" onclick="window.print()">
                Cetak Rekap (Print)
            </button>
            <a href="histori.php" class="btn-action btn-back">
                Kembali ke Panel
            </a>
        </div>
    </div>

    <div class="page-container">
        <div class="report-sheet" id="report-content">
            
            <header class="report-header">
                <div class="brand-box">
                    <img class="brand-logo" src="img/logo.png" alt="Kumaphone Logo">
                    <div class="brand-text">
                        <h1>KUMAPHONE SERVICE LAB</h1>
                        <p>Laporan Rekapitulasi Pemeriksaan Teknis Smartphone</p>
                    </div>
                </div>
                <div class="doc-meta">
                    <strong>TOTAL: <?= $totalCount ?> PERANGKAT</strong>
                    <span>Tanggal Cetak: <?= escape($printDate) ?> WIB</span>
                </div>
            </header>

            <div class="doc-title-bar">
                <h2>Laporan Riwayat Diagnosa Pelanggan</h2>
            </div>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th style="width: 22%;">Nama Customer</th>
                        <th style="width: 23%;">Merek &amp; Model</th>
                        <th style="width: 18%;">Nomor IMEI</th>
                        <th style="width: 10%;">OS</th>
                        <th style="width: 22%;">Hasil Diagnosa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historyList as $index => $row): ?>
                    <tr>
                        <td style="text-align: center; font-weight: bold;"><?= $index + 1 ?></td>
                        <td><strong><?= escape($row['brand']) ?></strong></td>
                        <td><?= escape($row['model']) ?></td>
                        <td class="imei-code"><?= escape($row['imei']) ?></td>
                        <td><?= escape($row['jenishp']) ?></td>
                        <td>
                            <span class="damage-tag"><?= escape($row['idkerusakan'] ?? '-') ?></span>
                            <?= escape($row['namakerusakan'] ?? 'Tidak Teridentifikasi') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="report-summary">
                <span>Dicetak oleh Administrator Kumaphone</span>
                <span>Kumaphone Expert System Engine &copy; <?= date('Y') ?></span>
            </div>

        </div>
    </div>

    <script>
        function exportToPDF() {
            const element = document.getElementById('report-content');
            const fileName = `Rekap-Histori-Diagnosa-Kumaphone-${new Date().toISOString().slice(0, 10)}.pdf`;

            const opt = {
                margin:       [8, 8, 8, 8],
                filename:     fileName,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>
</html>
