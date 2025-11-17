# 📞 Sistem Manajemen Kontak Sederhana (PHP & Tailwind CSS)

Ini adalah implementasi sederhana dari aplikasi manajemen kontak yang dibuat sebagai Tugas Akhir. Aplikasi ini menggunakan PHP murni untuk logika *backend* dan Tailwind CSS untuk *styling* antarmuka yang cepat dan modern.

## 🛠️ Fitur Utama

* **Autentikasi (Session Management):** Pengguna wajib login untuk mengakses fitur manajemen kontak.
* **CRUD (Create, Read, Update, Delete):** Fungsionalitas lengkap untuk mengelola data kontak.
* **Validasi Formulir:** Termasuk validasi dasar pada saat penambahan dan pengeditan kontak.
* **Database PDO:** Menggunakan PDO (PHP Data Objects) untuk interaksi database yang aman (melawan serangan SQL Injection).
* **Antarmuka Modern:** Menggunakan CDN Tailwind CSS untuk tampilan yang bersih dan responsif.

## ⚙️ Persyaratan Sistem

Untuk menjalankan aplikasi ini, Anda memerlukan:

1.  Web server lokal (misalnya **XAMPP, Laragon, WAMP**)
2.  PHP 7.4 atau lebih tinggi.
3.  Database **MySQL/MariaDB**.

## 🚀 Instalasi dan Setup

### 1. Struktur Proyek

Pastikan struktur folder proyek Anda di dalam direktori web server (misalnya, `htdocs/kontak-app`) sudah benar:

### 2. Konfigurasi Database

Buat database baru (contoh: `db_kontak`) dan jalankan skema SQL berikut:

```sql
-- Tabel untuk menyimpan kontak
CREATE TABLE kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    telepon VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel untuk manajemen user (login)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

```
Atau cukup impor saja file db_kontak.sql

### 3. Menjalankan Aplikasi
Akses aplikasi melalui browser: http://localhost/nama-folder-proyek/public/login.php

Kemudian masukan 
username: admin
password: admin123

