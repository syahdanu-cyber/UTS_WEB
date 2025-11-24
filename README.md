Berikut **README.md final** yang sudah rapi, profesional, dan siap langsung kamu upload ke GitHub.
Sudah mengikuti format tugas Project 1 dan Project 2 sesuai instruksi kampus.

---

# 🌱 **Edukasi Lingkungan — Project Web & CRUD API**

**Mata Kuliah:** Pemrograman Web 1
**Nama:** *ISI NAMA ANDA*
**NPM:** *ISI NPM ANDA*
**Kelas:** *ISI KELAS ANDA*

---

## 📑 **Daftar Isi**

* [Project 1 — Website Informasi](#project-1--website-informasi)
* [Struktur Website](#struktur-website)
* [Fitur Project 1](#fitur-project-1)
* [Project 2 — CRUD API](#project-2--crud-api)
* [Endpoint API](#endpoint-api)
* [Testing API (Postman & Bruno)](#testing-api-postman--bruno)
* [Struktur Folder Project](#struktur-folder-project)
* [Screenshot](#screenshot)
* [Footer](#footer)

---

# # 🌐 **Project 1 — Website Informasi**

Project ini adalah website edukasi lingkungan dengan beberapa halaman utama yang berisi artikel, galeri, edukasi, dan fitur login/registrasi.

Website dibangun menggunakan:

✔ PHP Native
✔ HTML + CSS
✔ Framework CSS (opsional)
✔ JavaScript untuk validasi login (menggunakan percabangan IF)

---

# ## 🧱 **Struktur Website**

### 1. **Halaman Utama — `index.php`**

* Menampilkan informasi umum edukasi lingkungan
* Navigasi ke halaman lain
* Menggunakan komponen navbar + footer
* 

### 2. **Halaman Artikel — `artikel.php`**

* Menampilkan daftar artikel
* Artikel dapat diklik untuk melihat detail

### 3. **Halaman Detail Artikel — `detail_artikel.php`**

* Menampilkan detail artikel sesuai ID yang dipilih

### 4. **Halaman Edukasi — `edukasi.php`**

* Berisi konten edukasi lingkungan dan informasi penting

### 5. **Halaman Galeri — `galeri.php`**

* Dokumentasi dan galeri foto edukasi lingkungan

### 6. **Halaman Tentang — `tentang.php`**

* Menjelaskan profil website dan tujuan edukasi

### 7. **Halaman Login — `user/login.php`**

* Validasi form menggunakan JavaScript (IF ELSE)
* Jika benar → pindah ke Halaman Menu Utama

### 8. **Halaman Registrasi — `user/register.php`**

* Pendaftaran akun baru
* Menyimpan data ke database

### 9. **Logout — `user/logout.php`**

---

# ## ⭐ **Fitur Project 1**

✔ Desain web menarik
✔ Halaman detail tampil setelah memilih informasi
✔ Validasi login memakai JavaScript (Percabangan IF)
✔ Footer tampil di semua halaman
✔ Navigasi web lengkap

---

# # 🔥 **Project 2 — CRUD API**

Project ini menambahkan API sederhana menggunakan PHP Native berdasarkan data dummy pada Project 1.

Folder API:
`/api/`

API mendukung operasi berikut:

* **CREATE** → tambah data user
* **READ** → tampilkan semua user
* **UPDATE** → ubah user
* **DELETE** → hapus user

Setiap output API menggunakan format JSON.

---

# ## 🧩 **Endpoint API**

## 🔹 **1. CREATE User**

**POST**
`/api/users/create.php`

Body:

```json
{
  "username": "john",
  "password": "12345"
}
```

---

## 🔹 **2. READ User**

**GET**
`/api/users/read.php`

Output contoh:

```json
{
  "success": true,
  "data": [
    {"id": 1, "username": "john"}
  ]
}
```

---

## 🔹 **3. UPDATE User**

**PUT**
`/api/users/update.php`

Body:

```json
{
  "id": 1,
  "username": "john_updated"
}
```

---

## 🔹 **4. DELETE User**

**DELETE**
`/api/users/delete.php`

Body sesuai kode kamu:

```json
{
  "id": 5
}
```

---

# # 🧪 **Testing API (Postman / Bruno)**

Untuk memenuhi tugas, sertakan screenshot:

✔ Test CREATE
✔ Test READ
✔ Test UPDATE
✔ Test DELETE

Body sudah disediakan untuk POST / PUT / DELETE.
Hasil output JSON wajib disertakan di README.

---

# # 📁 **Struktur Folder Project**

```
edukasi_lingkungan_project/
│
├── artikel.php
├── detail_artikel.php
├── edukasi.php
├── galeri.php
├── index.php
├── tentang.php
│
├── config/
│   └── koneksi.php
│
├── partials/
│   ├── footer.php
│   └── navbar.php
│
├── user/
│   ├── login.php
│   ├── logout.php
│   └── register.php
│
└── api/
    ├── config/
    │   └── database.php
    ├── helpers/
    │   ├── validate.php
    │   └── response.php
    ├── users/
    │   ├── create.php
    │   ├── read.php
    │   ├── update.php
    │   └── delete.php
    └── index.php
```

---

# # 📸 **Screenshot**

Tambahkan screenshot berikut ke repository GitHub:

### **Project 1 (Website)**

* Home
* Artikel
* Detail Artikel
* Edukasi
* Galeri
* Tentang
* Login/Registrasi

### **Project 2 (API)**

* Postman Create
* Postman Read
* Postman Update
* Postman Delete

### **Struktur Folder**

---

# # 📝 **Footer**

Footer yang muncul di semua halaman:

```
©Copyright by NPM_NAMA MAHASISWA_KELAS
```

---

# # 🎯 **Penutup**

Repository ini berisi dua project:

✔ **Project 1 – Website Edukasi Lingkungan**
✔ **Project 2 – CRUD API berbasis PHP Native**

Keduanya berada dalam satu repository sesuai ketentuan tugas.

Jika kamu ingin, saya bisa:
👉 buatkan versi README yang memakai markdown dengan tabel
👉 tambahkan badge GitHub
👉 buatkan dokumentasi API versi profesional (seperti Swagger)

Tinggal bilang saja!
