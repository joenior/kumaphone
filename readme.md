# Kumaphone - Sistem Pakar Diagnosa Kerusakan Smartphone Android menggunakan Metode Forward Chaining

## Apa itu Kumaphone?
Sistem Pakar Diagnosa Kerusakan Smartphone Android menggunakan Metode Forward Chaining adalah sebuah program yang dapat digunakan untuk mendiagnosis kerusakan pada smartphone Android. Program ini menggunakan metode forward chaining untuk mencari solusi dari kerusakan pada smartphone Android yang diberikan.

## Metode Forward Chaining
Metode Forward Chaining adalah metode yang digunakan dalam sistem pakar untuk mencari solusi dari masalah. Metode ini bekerja dengan mengambil fakta-fakta yang tersedia dan mencocokkannya dengan aturan-aturan yang ada dalam basis pengetahuan. Aturan-aturan ini kemudian digunakan untuk membuat keputusan dan menghasilkan solusi untuk masalah yang diberikan.

## Cara Kerja
Untuk menggunakan program ini, pengguna harus memasukkan gejala-gejala yang dialami oleh smartphone Android yang ingin didiagnosis kerusakannya. Setelah itu, program akan mencari solusi kerusakan yang mungkin terjadi dengan menggunakan aturan-aturan yang ada dalam basis pengetahuan. Program akan mengeluarkan hasil diagnosa kerusakan dan solusinya.

## Basis Pengetahuan
Basis pengetahuan dalam program ini berisi aturan-aturan yang digunakan untuk mencari solusi dari kerusakan pada smartphone Android. Aturan-aturan ini disusun berdasarkan gejala-gejala yang dialami oleh smartphone Android. 

## Persyaratan
- Browser (Google Chrome, Firefox, Opera, Safari, dll)
- Koneksi Internet (Untuk terhubung dengan CDN Tailwind dan Flowbite sebagai Frontend website)
- XAMPP/Laragon (PHP 7.4/8.0 & MySQL)
- Visual Studio Code / VSCodium

## Pembaruan & Peningkatan Keamanan (Refactoring)
Proyek ini telah melalui pembaruan besar (refactoring) untuk meningkatkan keamanan, kinerja, serta kerapihan struktur kode:

- **Keamanan Database (PDO & Prepared Statements):** Seluruh query database telah dimigrasikan dari `mysqli` langsung ke **PDO dengan Prepared Statements** di `includes/database.php` untuk mencegah serangan **SQL Injection**.
- **Keamanan Otentikasi Admin:** Sistem login kini menggunakan `password_hash()` dan `password_verify()` (Bcrypt) untuk keamanan password.
- **Proteksi CSRF & XSS:** Ditambahkan fitur penanganan **CSRF Token** pada form serta fungsi sanitasi output (`escape()`) untuk mencegah **Cross-Site Scripting (XSS)**.
- **Modularisasi Layout & UI:** Komponen header, navigasi, sidebar admin, dan footer telah dipisahkan ke dalam folder `includes/` (`navigation.php`, `admin_sidebar.php`, `admin_header.php`, `footer.php`) dan CSS publik ditata secara rapi di `assets/css/`.
- **Konfigurasi Environment:** Koneksi database dapat disesuaikan menggunakan Environment Variables (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).

## Instalasi
Siapkan XAMPP/Laragon terlebih dahulu untuk memulai server PHP dan MySQL.
Pastikan juga kamu sudah menginstall git sebelum melakukan clone untuk repositori seperti berikut:

```bash
git clone https://github.com/joenior/kumaphone
```
Lalu tempatkan folder kumaphone pada folder htdocs jika menggunakan XAMPP, atau folder www jika menggunakan Laragon.

Setelah itu, buatlah database dengan nama 'sbp' lalu import file `db_sbp.sql` ke database MySQL.

## Screenshots
Light mode (System adaptive)
![Light](https://i.ibb.co/4RKJ5cy/Kumaphone-Personal-Microsoft-Edge-Canary-b1.png)

Dark mode
![App Screenshot](https://i.ibb.co/HVBpdh2/Kumaphone-Personal-Microsoft-Edge-Canary-1-b1.png)

## License
[MIT](https://choosealicense.com/licenses/mit/)

## Demo
[Preview](https://kumaphone.my.id)
[Hosted by IjulHost]

## Support
Jika perlu bantuan, silahkan hubungi melalui [telegram](t.me/joenior)
