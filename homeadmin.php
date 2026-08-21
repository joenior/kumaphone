<?php
/**
 * Admin Dashboard - Home Page
 *
 * Shows the customer history table, system overview KPIs, and bulk management.
 */
require_once 'session.php';
requireAdmin();

$activeMenu = 'beranda';
$adminName  = getAdminName($db) ?? 'Administrator';

// Fetch customer records joined with damage details
$customerRecords = fetchAll(
    $db,
    "SELECT s.*, k.namakerusakan, k.jenishp AS kategori_hp
     FROM smartphones s
     LEFT JOIN kerusakan k ON s.idkerusakan = k.idkerusakan
     ORDER BY s.id DESC"
);

// Fetch KPI statistics
$totalDiagnosa = count($customerRecords);
$totalKerusakan = (int)(fetchOne($db, "SELECT COUNT(*) as cnt FROM kerusakan")['cnt'] ?? 0);
$totalGejala = (int)(fetchOne($db, "SELECT COUNT(*) as cnt FROM gejala")['cnt'] ?? 0);
$totalAturan = (int)(fetchOne($db, "SELECT COUNT(*) as cnt FROM basispengetahuan")['cnt'] ?? 0);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Admin - Kumaphone</title>
    <link rel="icon" href="favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&amp;family=Manrope:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="dashboard.css" rel="stylesheet">
</head>
<body>

<?php include 'includes/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/admin_sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 admin-main">
            <!-- Page Header -->
            <div class="admin-page-header">
                <p class="admin-greeting-badge"><span></span>Panel Kontrol Pakar</p>
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h1 class="admin-page-title">Dasbor Administrator</h1>
                        <p class="admin-page-subtitle">Ringkasan riwayat pemeriksaan teknis dan basis pengetahuan sistem pakar Kumaphone.</p>
                    </div>
                </div>
            </div>

            <!-- KPI Metrics Grid -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap blue">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <span class="kpi-tag">Customer</span>
                    </div>
                    <p class="kpi-value"><?= number_format($totalDiagnosa) ?></p>
                    <p class="kpi-label">Total Pemeriksaan Selesai</p>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap green">
                            <i class="bi bi-phone-fill"></i>
                        </div>
                        <span class="kpi-tag">Kategori</span>
                    </div>
                    <p class="kpi-value"><?= number_format($totalKerusakan) ?></p>
                    <p class="kpi-label">Jenis Kerusakan Terdaftar</p>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap amber">
                            <i class="bi bi-clipboard2-pulse-fill"></i>
                        </div>
                        <span class="kpi-tag">Kondisi</span>
                    </div>
                    <p class="kpi-value"><?= number_format($totalGejala) ?></p>
                    <p class="kpi-label">Ciri Kerusakan / Gejala</p>
                </div>

                <div class="kpi-card">
                    <div class="kpi-header">
                        <div class="kpi-icon-wrap purple">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <span class="kpi-tag">Forward Chaining</span>
                    </div>
                    <p class="kpi-value"><?= number_format($totalAturan) ?></p>
                    <p class="kpi-label">Aturan Basis Pengetahuan</p>
                </div>
            </div>

            <!-- Data Table Card -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title-group">
                        <h2 class="admin-card-title">Riwayat Pemeriksaan Customer</h2>
                        <span class="admin-card-count" id="recordCount"><?= count($customerRecords) ?> Data</span>
                    </div>
                    
                    <div class="admin-card-controls">
                        <!-- Fast Search Input -->
                        <div class="admin-search-wrap">
                            <i class="bi bi-search admin-search-icon"></i>
                            <input type="text" id="searchInput" class="admin-search-input" placeholder="Cari nama, model, IMEI, atau kerusakan..." autocomplete="off">
                        </div>

                        <!-- Delete All Trigger -->
                        <?php if (!empty($customerRecords)): ?>
                        <button type="button" class="btn-danger-outline" data-bs-toggle="modal" data-bs-target="#deleteHistoryModal">
                            <i class="bi bi-trash3"></i>
                            <span>Hapus Semua Data</span>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table-modern" id="customerTable">
                        <thead>
                            <tr>
                                <th class="cell-index">No</th>
                                <th>Nama Customer</th>
                                <th>Perangkat &amp; Model</th>
                                <th>IMEI</th>
                                <th>Hasil Diagnosis</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($customerRecords)): ?>
                            <tr>
                                <td colspan="6">
                                    <div class="table-empty-state">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <h3>Belum Ada Riwayat Pemeriksaan</h3>
                                        <p>Data pemeriksaan baru akan otomatis tercatat di sini setelah pengguna menjalankan proses diagnosis.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php else: ?>
                                <?php foreach ($customerRecords as $index => $record): 
                                    $customerName = $record['brand'] ?? 'Pelanggan';
                                    $initials = strtoupper(substr($customerName, 0, 2));
                                    $damageId = $record['idkerusakan'] ?? '-';
                                    $damageName = $record['namakerusakan'] ?? 'Tidak Teridentifikasi';
                                    $model = $record['model'] ?? '-';
                                    $imei = $record['imei'] ?? '-';
                                    $os = $record['jenishp'] ?? 'Android';
                                ?>
                                <tr class="data-row">
                                    <td class="cell-index"><?= sprintf('%02d', $index + 1) ?></td>
                                    <td>
                                        <div class="customer-cell">
                                            <div class="customer-avatar" aria-hidden="true"><?= escape($initials) ?></div>
                                            <span class="customer-name"><?= escape($customerName) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="device-chip">
                                            <span><?= escape($model) ?></span>
                                            <span class="device-badge"><?= escape($os) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="imei-code" title="Nomor IMEI"><?= escape($imei) ?></span>
                                    </td>
                                    <td>
                                        <span class="damage-badge k-item">
                                            <span class="code-tag"><?= escape($damageId) ?></span>
                                            <span><?= escape($damageName) ?></span>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-monospace small"><?= escape($record['kategori_hp'] ?? $os) ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Empty search result alert -->
                    <div id="noMatchMessage" class="table-empty-state d-none">
                        <div class="empty-state-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <h3>Data Tidak Ditemukan</h3>
                        <p>Tidak ada data customer yang cocok dengan kata kunci pencarian Anda.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Riwayat -->
<div class="modal fade" id="deleteHistoryModal" tabindex="-1" aria-labelledby="deleteHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-warning-icon">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h3 class="modal-custom-title" id="deleteHistoryModalLabel">Hapus Seluruh Data Riwayat?</h3>
            <p class="modal-custom-text">Tindakan ini bersifat permanen dan akan menghapus seluruh catatan pemeriksaan customer yang tersimpan di sistem.</p>
            
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light px-3 py-2 fw-bold" data-bs-dismiss="modal">Batal</button>
                <form method="post" action="deletehistori.php" class="m-0">
                    <?= csrfField() ?>
                    <button type="submit" class="btn btn-danger px-3 py-2 fw-bold d-inline-flex align-items-center gap-2">
                        <i class="bi bi-trash3"></i>
                        <span>Ya, Hapus Semua</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="bootstrap.bundle.min.js"></script>
<script>
  // Real-time client search filter
  const searchInput = document.getElementById('searchInput');
  const tableRows = document.querySelectorAll('#customerTable tbody tr.data-row');
  const noMatchMessage = document.getElementById('noMatchMessage');
  const recordCountBadge = document.getElementById('recordCount');

  if (searchInput && tableRows.length > 0) {
    searchInput.addEventListener('input', (e) => {
      const query = e.target.value.toLowerCase().trim();
      let matchedCount = 0;

      tableRows.forEach(row => {
        const text = row.innerText.toLowerCase();
        if (text.includes(query)) {
          row.style.display = '';
          matchedCount++;
        } else {
          row.style.display = 'none';
        }
      });

      if (matchedCount === 0 && query !== '') {
        if (noMatchMessage) noMatchMessage.classList.remove('d-none');
      } else {
        if (noMatchMessage) noMatchMessage.classList.add('d-none');
      }

      if (recordCountBadge) {
        recordCountBadge.textContent = `${matchedCount} Data`;
      }
    });
  }
</script>
</body>
</html>
