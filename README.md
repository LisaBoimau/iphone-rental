# 📱 Sistem Informasi Manajemen Peminjaman iPhone (iPhone Rental)

Sistem Informasi Manajemen Peminjaman iPhone adalah aplikasi berbasis web yang dibangun menggunakan **Laravel** untuk mempermudah operasional rental unit iPhone. Sistem ini dilengkapi fitur otomatisasi label QR Code unik per unit, pemindaian QR (kamera & input manual), hitung mundur durasi peminjaman (*real-time countdown timer*), verifikasi identitas penyewa, dan pencatatan riwayat transaksi secara terstruktur.

---

## 🚀 Fitur Utama

- **Dashboard Real-time:**
  - Monitoring penyewa yang sedang aktif.
  - Detail customer cepat (Modal Popup: Nama, WhatsApp, Alamat, No. KTP, dan pratinjau Foto KTP).
  - Countdown timer sisa waktu sewa secara *real-time*.
  - Aksi tombol pengembalian instan setelah masa sewa berakhir.

- **Manajemen Data iPhone:**
  - Tambah, ubah, dan hapus unit (Tipe iPhone & nomor IMEI).
  - Generate otomatis QR Code unik untuk tiap perangkat.
  - Tombol **Print QR** siap cetak untuk ditempel pada boks fisik iPhone.
  - Status unit otomatis berubah (*Tersedia* / *Dipinjam*).

- **Transaksi Peminjaman Fleksibel:**
  - Opsi scan label boks langsung menggunakan **Kamera (QR Scanner)**.
  - Opsi **Input Manual** untuk memilih unit yang sedang berstatus *Tersedia*.
  - Pemilihan customer terdaftar atau langsung input data customer baru.
  - Pengaturan durasi peminjaman dalam hitungan jam.

- **Database Customer:**
  - Penyimpanan data penyewa lengkap dengan nomor identitas dan berkas foto KTP.
  - Integrasi kontak WhatsApp untuk mempermudah tindak lanjut.

- **Riwayat Transaksi:**
  - Log riwayat penyewaan lengkap mencakup waktu pinjam, waktu pengembalian aktual, serta rekap denda keterlambatan (jika ada).

---

## 🛠️ Tech Stack

- **Backend:** Laravel 10 / 11, PHP 8.2+
- **Database:** MySQL
- **Frontend:** Blade Templating, Tailwind CSS, Vanilla JavaScript
- **Tools / Library Tambahan:** QR Code Generator, HTML5 QR Code Scanner / WebRTC Camera API

---

## 💻 Panduan Instalasi Lokal

1. **Clone Repositori:**
   ```bash
   git clone [https://github.com/LisaBoimau/iphone-rental.git](https://github.com/LisaBoimau/iphone-rental.git)
   cd iphone-rental

---

1. Install Dependensi Composer & Node:
   ```Bash
   composer install
   npm install && npm run build

2. Konfigurasi Environment:
Salin file konfigurasi contoh menjadi .env:
    ```Bash
    cp .env.example .env

Buka file .env dan sesuaikan koneksi database MySQL:
   ``` Cuplikan kode
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=iphone_rental
    DB_USERNAME=root
    DB_PASSWORD=
```
3. Generate Application Key & Storage Link:
    ```Bash
    php artisan key:generate
    php artisan storage:link
    
4. Migrasi Database:(Pastikan database iphone_rental sudah dibuat terlebih dahulu di MySQL/phpMyAdmin)
    ```Bash
    php artisan migrate

5. Clear Config & Jalankan Server Lokal:
    ```Bash
    php artisan config:cache
    php artisan serve
Akses aplikasi melalui browser di: http://127.0.0.1:8000
