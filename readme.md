# Kumaphone

Sistem Pakar Diagnosa Kerusakan Smartphone Berbasis Metode Forward Chaining dengan Fasilitas Penjelasan, Pencatatan Nomor Registrasi Unik, Pusat Pelacakan Mandiri, dan Ekspor Laporan PDF Resmi.

---

## Daftar Isi

- [Tentang Program](#tentang-program)
- [Metode Forward Chaining & Sistem Inferensi](#metode-forward-chaining--sistem-inferensi)
- [Fitur Utama Sistem](#fitur-utama-sistem)
- [Struktur Direktori Proyek](#struktur-direktori-proyek)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Panduan Instalasi](#panduan-instalasi)
- [Alur Kerja Sistem](#alur-kerja-sistem)
- [Keamanan dan Kualitas Kode](#keamanan-dan-kualitas-kode)
- [Lisensi](#lisensi)

---

## Tentang Program

Kumaphone adalah aplikasi sistem pakar yang dirancang untuk membantu teknisi laboratorium dan pengguna umum dalam mengidentifikasi jenis kerusakan pada smartphone Android. Sistem melakukan inferensi berdasarkan sekumpulan fakta atau ciri-ciri fisik dan fungsional yang ditemukan pada perangkat, kemudian menyusun kesimpulan kerusakan yang paling relevan beserta langkah-langkah penanganan teknisnya.

---

## Metode Forward Chaining & Sistem Inferensi

Sistem mengadopsi metode penalaran maju (*Forward Chaining*), yaitu proses penarikan kesimpulan yang bergerak dari fakta-fakta yang diketahui menuju hipotesis atau kesimpulan akhir:

1. **Pengumpulan Fakta**: Pengguna memilih sekumpulan gejala yang dialami oleh smartphone.
2. **Evaluasi Premis Aturan**: Mesin inferensi mencocokkan fakta yang dipilih dengan aturan-aturan di tabel basis pengetahuan (`basispengetahuan` JOIN `kerusakan`).
3. **Perhitungan Derajat Kecocokan (*Coverage Scoring*)**:
   $$\text{Skor Kecocokan} = \text{round}\left(\frac{\text{Jumlah Gejala Cocok}}{\text{Total Gejala Syarat Kerusakan}} \times 100\right)\%$$
4. **Klasifikasi Hasil**:
   - **Exact Match (100%)**: Seluruh syarat aturan terpenuhi sempurna.
   - **Partial Match (< 100%)**: Sebagian syarat terpenuhi, diurutkan berdasarkan persentase tertinggi.
5. **Resolusi Konflik (*Conflict Resolution*)**:
   Jika beberapa kerusakan memiliki gejala yang cocok, sistem menyortir kesimpulan berdasarkan:
   - Persentase kecocokan tertinggi (`match_percentage` DESC)
   - Jumlah gejala cocok terbanyak (`matched_count` DESC)
   - Urutan abjad ID kerusakan (`idkerusakan` ASC)
   Peringkat teratas secara otomatis ditetapkan sebagai **Rekomendasi Utama**.

---

## Fitur Utama Sistem

### 1. Mesin Inferensi & Fasilitas Penjelasan (Explanation Facility)
- **Rekomendasi Utama Terverifikasi**: Menyoroti kemungkinan kerusakan dengan probabilitas tertinggi.
- **Transparansi Aturan**: Menampilkan rincian gejala yang cocok dengan pilihan pengguna dan gejala syarat yang belum terpenuhi untuk transparansi teknisi.
- **Tautan Solusi Cepat**: Navigasi langsung ke panduan perbaikan bertahap.

### 2. Nomor Registrasi Unik Permanen
- Format nomor registrasi standar industri: `KMP-YYYYMMDD-XXXX` (contoh: `KMP-20260821-0001`).
- Dibuat secara otomatis dan disimpan permanen pada kolom `no_registrasi` tabel `smartphones` bersama stempel waktu `created_at`.
- Dilengkapi sistem migrasi otomatis schema database (`ensureSmartphoneSchema`) yang melakukan penyesuaian kolom dan backfill data lama secara instan.

### 3. Pusat Pelacakan Mandiri (tracking.php)
- **Pencarian Terpadu**: Pelanggan atau teknisi dapat melacak riwayat diagnosa menggunakan **Nomor Registrasi** maupun **Nomor IMEI 15-digit**.
- **Kartu Informasi Lengkap**: Menampilkan status verifikasi laboratorium, spesifikasi perangkat, tanggal diagnosa riil, dan kesimpulan kerusakan.
- **Aksi Cepat Dokumen**: Tombol akses langsung untuk membuka/mengunduh ulang lembar PDF tanpa perlu input ulang formulir.

### 4. Lembar Hasil Pemeriksaan Resmi & Ekspor PDF (cetak_diagnosa.php)
- **Kop Surat Laboratorium**: Identitas resmi "KUMAPHONE SERVICE LAB" lengkap dengan nomor dokumen, tanggal pemeriksaan, dan rincian perangkat.
- **Dual-Export Mechanism**:
  - **Unduh PDF Langsung (.pdf)**: Menggunakan engine `html2pdf.js` untuk mengunduh dokumen secara instan di sisi klien.
  - **Cetak Dokumen (Print)**: Pengaturan CSS `@media print` A4 portrait yang bersih tanpa elemen bilah navigasi atau artefak web.
- **Lembar Pengesahan Tanda Tangan**: Kolom tanda tangan pemilik perangkat dan teknisi pemeriksa.

### 5. Panel Administrator & Rekapitulasi Laporan (homeadmin.php, histori.php, cetak_histori.php)
- **Dasbor Metrik KPI**: Kartu statistik total pemeriksaan selesai, jumlah kategori kerusakan, ciri gejala, dan aturan basis pengetahuan.
- **Tabel Histori Terpadu**: Tabel riwayat pelanggan lengkap dengan kolom Nomor Registrasi, filter pencarian instan, dan tombol cetak lembar diagnosa individual.
- **Rekapitulasi Histori PDF**: Lembar cetak rekapitulasi data massal (A4 landscape) untuk keperluan audit teknisi dan manajemen service center.
- **Navigasi Sidebar Lengkap**: Menu sidebar yang terkelompok rapi (Menu Utama, Laporan & Cetak, Sistem & Navigasi).

---

## Struktur Direktori Proyek

```text
kumaphone/
├── assets/
│   └── css/
│       ├── dashboard.css          # Styling dasbor admin dan sidebar
│       ├── public-diagnosis.css    # Styling alur diagnosis dan fasilitas penjelasan
│       ├── public-home.css         # Styling halaman beranda dan layout umum
│       └── public-tracking.css     # Styling pusat pelacakan diagnosa
├── includes/
│   ├── admin_header.php            # Header bilah atas panel admin
│   ├── admin_sidebar.php           # Navigasi sidebar panel admin
│   ├── auth.php                    # Middleware otentikasi admin
│   ├── database.php                # Koneksi PDO, helper query, dan proteksi CSRF
│   ├── diagnosis_engine.php        # Mesin inferensi Forward Chaining & helper registrasi
│   ├── footer.php                  # Footer standar
│   └── navigation.php              # Navigasi umum
├── index.html                      # Halaman utama publik
├── diagnosa.php                    # Halaman formulir dan hasil diagnosis
├── tracking.php                    # Halaman pelacakan status diagnosa
├── cetak_diagnosa.php              # Lembar cetak laporan diagnosa resmi & ekspor PDF
├── cetak_histori.php               # Lembar cetak rekapitulasi histori admin
├── kerusakan.php                   # Katalog data ciri kerusakan
├── detailkerusakan1.php            # Detail kerusakan dan panduan langkah perbaikan
├── homeadmin.php                   # Dasbor utama administrator
├── histori.php                     # Halaman riwayat pemeriksaan administrator
├── daftarkerusakan.php             # Master data kerusakan admin
├── gejala.php                      # Master data gejala admin
├── basispengetahuan.php            # Master data relasi aturan admin
├── inputkerusakan.php              # Form penambahan data kerusakan
├── ainputgejala.php                # Form penambahan data gejala
├── abasispengetahuan.php           # Form penambahan data relasi aturan
├── login.html                      # Halaman login administrator
├── ceklogin.php                    # Logika verifikasi login admin
├── logout.php                      # Logout session admin
├── db_sbp.sql                      # Skema dan dump basis data MySQL
└── readme.md                       # Dokumentasi resmi proyek
```

---

## Persyaratan Sistem

- **Web Server**: Apache / Nginx (XAMPP, Laragon, atau native server).
- **Bahasa Pemrograman**: PHP versi 7.4 atau PHP 8.x.
- **Basis Data**: MySQL versi 5.7+ atau MariaDB versi 10.3+.
- **Web Browser**: Google Chrome, Mozilla Firefox, Microsoft Edge, Safari, Opera (mendukung ES6 dan CSS modern).

---

## Panduan Instalasi

### 1. Kloning Repositori
Tempatkan folder proyek ke dalam direktori root server web Anda:
- XAMPP: `C:/xampp/htdocs/kumaphone`
- Laragon: `C:/laragon/www/kumaphone`

```bash
git clone https://github.com/joenior/kumaphone.git
```

### 2. Konfigurasi Basis Data
1. Buka antarmuka phpMyAdmin atau terminal MySQL.
2. Buat basis data baru bernama `sbp`:
   ```sql
   CREATE DATABASE sbp CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
   ```
3. Impor berkas `db_sbp.sql` ke dalam basis data `sbp`.

### 3. Konfigurasi Koneksi (Opsional)
Konfigurasi database default di [includes/database.php](includes/database.php) menggunakan host `localhost`, pengguna `root`, tanpa password, dan nama basis data `sbp`. Anda juga dapat menggunakan Environment Variables:
- `DB_HOST`: Host database (default: `localhost`)
- `DB_USER`: Pengguna database (default: `root`)
- `DB_PASS`: Kata sandi database (default: kosong)
- `DB_NAME`: Nama database (default: `sbp`)

### 4. Akses Aplikasi
- **Aplikasi Publik**: Buka browser dan akses `http://localhost/kumaphone/`
- **Halaman Diagnosis**: `http://localhost/kumaphone/diagnosa.php`
- **Pelacakan Dokumen**: `http://localhost/kumaphone/tracking.php`
- **Panel Administrator**: `http://localhost/kumaphone/login.html`

---

## Alur Kerja Sistem

### Alur Pelanggan / Teknisi Lapangan
1. **Pengisian Data**: Masukkan nama pelanggan, merek & model smartphone, dan nomor IMEI.
2. **Pemilihan Gejala**: Tandai seluruh ciri kerusakan fisik maupun sistemik yang ditemukan pada perangkat.
3. **Eksekusi Inferensi**: Mesin Forward Chaining memproses aturan, menghitung skor kecocokan, dan menyimpan data riwayat.
4. **Penerbitan Nomor Registrasi**: Sistem menerbitkan kode unik (misal: `KMP-20260821-0001`).
5. **Ekspor & Cetak**: Pengguna dapat langsung mengunduh berkas laporan resmi berformat PDF atau mencetaknya.
6. **Pelacakan Berkala**: Di kemudian hari, status dan berkas laporan dapat dilacak kembali melalui halaman `tracking.php` menggunakan kode registrasi atau IMEI.

### Alur Administrator
1. **Login Keamanan**: Masuk melalui portal admin dengan otentikasi terenkripsi.
2. **Monitoring Dasbor**: Memantau KPI statistik volume pemeriksaan dan data basis pengetahuan.
3. **Manajemen Master Data**: Menambah, mengubah, atau menghapus data kerusakan, gejala, dan relasi aturan Forward Chaining.
4. **Audit & Rekapitulasi**: Memeriksa riwayat pemeriksaan pelanggan dan mencetak laporan rekapitulasi berkala.

---

## Keamanan dan Kualitas Kode

- **Pencegahan SQL Injection**: Seluruh interaksi database menggunakan PDO dengan Prepared Statements dan parameter binding.
- **Keamanan Otentikasi**: Penyimpanan kata sandi administrator mengimplementasikan hashing aman Bcrypt (`password_hash` dan `password_verify`).
- **Pencegahan Cross-Site Scripting (XSS)**: Seluruh output dinamis disanitasi menggunakan fungsi `escape()` berbasis `htmlspecialchars()`.
- **Proteksi CSRF**: Pengiriman form penting diverifikasi menggunakan token anti-CSRF per sesi.
- **Desain Bebas Ketergantungan Berat**: Tata letak dan komponen visual dibangun menggunakan CSS Vanilla terstruktur dan ringan.

---

## Lisensi

Proyek ini didistribusikan di bawah lisensi [MIT License](LICENSE).
