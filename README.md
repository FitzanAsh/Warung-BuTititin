# 🍜 Warung Bu Titin - E-Commerce Website

<div align="center">
  <img src="assets/images/WarungButitin2.png" alt="Warung Bu Titin Logo" width="200"/>
  
  **Warung pagi dengan menu sehat dan bergizi. Temukan makanan favoritmu hanya di sini!**
  
  [![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  [![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
  [![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
  [![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
</div>

---

## 📋 Deskripsi

**Warung Bu Titin** adalah sebuah website e-commerce untuk warung makanan tradisional Indonesia. Website ini memungkinkan pelanggan untuk melihat menu, memesan makanan dan minuman, serta melakukan pengelolaan pesanan secara online.

---

## ✨ Fitur Utama

### 👤 Fitur Pelanggan (User)
- 🔐 **Autentikasi** - Register dan Login dengan sistem session
- 🛒 **Keranjang Belanja** - Tambah, hapus, dan kelola item di keranjang
- 📦 **Pemesanan** - Proses checkout dengan input alamat dan nomor telepon
- 📋 **Riwayat Pesanan** - Lihat status dan riwayat pesanan
- 🔔 **Notifikasi** - Notifikasi real-time untuk update status pesanan
- 🔍 **Pencarian Produk** - Cari produk berdasarkan nama
- 📱 **Responsif** - Tampilan yang responsif untuk berbagai ukuran layar

### 👨‍💼 Fitur Admin
- 📊 **Dashboard Admin** - Kelola seluruh operasional warung
- ➕ **Manajemen Produk** - Tambah, edit, dan hapus produk
- 📝 **Manajemen Pesanan** - Update status pesanan (Baru → Diproses → Dikirim → Sampai)
- 👥 **Manajemen Pelanggan** - Lihat daftar pelanggan terdaftar
- 📧 **Pesan Kontak** - Kelola pesan dari formulir "Hubungi Kami"

---

## 🛠️ Teknologi yang Digunakan

### Backend
| Teknologi | Versi | Deskripsi |
|-----------|-------|-----------|
| PHP | 7.4+ | Server-side scripting |
| MySQL/MariaDB | 10.4+ | Database management |
| Apache | 2.4+ | Web server |

### Frontend
| Teknologi | Deskripsi |
|-----------|-----------|
| HTML5 | Struktur halaman web |
| CSS3 | Styling dan animasi |
| JavaScript | Interaktivitas client-side |
| Google Fonts | Typography (Poppins, Montserrat, Lemon) |

### Tools & Environment
| Tool | Deskripsi |
|------|-----------|
| XAMPP | Local development environment |
| HeidiSQL | Database management tool |

---

## 📁 Struktur Project

```
WarungButitin/
├── 📁 Database/
│   └── warungbutitin.sql       # Database dump file
├── 📁 assets/
│   ├── 📁 images/              # Gambar produk dan aset
│   ├── style.css               # Stylesheet utama
│   ├── script.js               # JavaScript utama
│   ├── PublicaSans-Light.woff  # Custom font
│   └── PublicaSans-Medium.woff # Custom font
├── 📁 includes/
│   ├── header.php              # Header template
│   ├── footer.php              # Footer template
│   ├── footer2.php             # Footer alternatif
│   └── mark_as_read.php        # Handler notifikasi
├── 📁 pages/
│   ├── 📁 admin/
│   │   ├── admin.php           # Dashboard admin
│   │   ├── add_product.php     # Tambah produk
│   │   ├── edit_product.php    # Edit produk
│   │   ├── pesanan.php         # Manajemen pesanan
│   │   └── pelanggan.php       # Manajemen pelanggan
│   ├── login.php               # Halaman login
│   ├── register.php            # Halaman registrasi
│   ├── products.php            # Daftar produk
│   ├── product_detail.php      # Detail produk
│   ├── keranjang.php           # Keranjang belanja
│   ├── pesanan_saya.php        # Riwayat pesanan user
│   ├── user.php                # Dashboard user
│   ├── about.php               # Halaman tentang kami
│   └── ...                     # File pendukung lainnya
├── db_connect.php              # Konfigurasi koneksi database
├── functions.php               # Helper functions
├── index.php                   # Landing page
└── README.md                   # Dokumentasi project
```

---

## 🗄️ Struktur Database

Database `warungbutitin` terdiri dari tabel-tabel berikut:

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Data pengguna (admin & customer) |
| `categories` | Kategori produk (Makanan, Minuman) |
| `products` | Data produk |
| `cart` | Keranjang belanja user |
| `orders` | Data pesanan |
| `order_items` | Detail item dalam pesanan |
| `notifications` | Notifikasi status pesanan |
| `reviews` | Ulasan produk dari pelanggan |
| `hubungikami` | Pesan dari formulir kontak |

### ERD (Entity Relationship)
```
users ──────< orders ──────< order_items >────── products
  │                              │
  │                              └── categories
  │
  └──────< cart >────── products
  │
  └──────< reviews >──── products
```

---

## 🚀 Cara Instalasi

### Prasyarat
- XAMPP (atau WAMP/MAMP) dengan PHP 7.4+ dan MySQL/MariaDB
- Web browser modern

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/FitzanAsh/Warung-BuTititin.git
   ```

2. **Pindahkan ke folder htdocs**
   ```bash
   # Windows
   mv Warung-BuTititin C:/xampp/htdocs/WarungButitin
   
   # Linux/Mac
   mv Warung-BuTititin /opt/lampp/htdocs/WarungButitin
   ```

3. **Start XAMPP**
   - Jalankan Apache dan MySQL melalui XAMPP Control Panel

4. **Import Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Buat database baru dengan nama: `warungbutitin`
   - Import file: `Database/warungbutitin.sql`

5. **Konfigurasi Database** (jika diperlukan)
   
   Edit file `db_connect.php`:
   ```php
   $host = "localhost";
   $username = "root";
   $password = "";
   $database = "warungbutitin";
   ```

6. **Akses Website**
   ```
   http://localhost/WarungButitin/
   ```

---

## 👤 Akun Default

### Admin
| Username | Password |
|----------|----------|
| admin1 | admin123 |
| admin2 | admin123 |

### User (Contoh)
| Username | Password |
|----------|----------|
| atha | (hashed) |
| rangga | (hashed) |

> ⚠️ **Catatan:** Password user ter-hash menggunakan `password_hash()`. Untuk testing, silakan registrasi akun baru.

---

## 🍽️ Menu Produk

### Kategori Makanan
| Produk | Harga |
|--------|-------|
| Ayam Bakar | Rp 20.000 |
| Lontong Sayur | Rp 7.000 |
| Nasi Gurih | Rp 7.000 |
| Serabi | Rp 5.000 |
| Kue Lupis | Rp 5.000 |
| Bakwan | Rp 2.000 |

### Kategori Minuman
| Produk | Harga |
|--------|-------|
| Es Cokelat | Rp 12.000 |
| Jus Jeruk | Rp 8.000 |
| Kopi | Rp 6.000 |
| Teh Manis | Rp 5.000 |

---

## 📸 Screenshots

### Landing Page
Halaman utama dengan desain modern menggunakan glassmorphism effect dan background image yang menarik.

### Dashboard User
Menampilkan produk-produk yang tersedia dengan kategori Makanan dan Minuman.

### Admin Dashboard
Panel admin untuk mengelola produk, pesanan, dan pelanggan.

---

## 🔐 Fitur Keamanan

- ✅ Password hashing menggunakan `password_hash()` (bcrypt)
- ✅ Session-based authentication
- ✅ Remember me token untuk persistent login
- ✅ Role-based access control (Admin/User)
- ✅ Input validation pada form

---

## 🎨 Desain UI/UX

- **Color Palette:**
  - Primary: `#F26421` (Orange)
  - Secondary: `#5CBA47` (Green)
  - Dark: `#1B1817`
  - Light: `#FFFFFF`

- **Typography:**
  - Heading: Lemon, Titan One
  - Body: Poppins, Montserrat
  - Custom: PublicaSans

- **Effects:**
  - Glassmorphism
  - Smooth animations
  - Hover effects
  - Box shadows

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Jika ingin berkontribusi:

1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/FiturBaru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin feature/FiturBaru`)
5. Buat Pull Request

---

## 📝 Lisensi

Project ini dibuat untuk keperluan pembelajaran dan pengembangan.

---

## 👨‍💻 Developer

Dikembangkan dengan ❤️ oleh **Fitzan Ashari**

[![GitHub](https://img.shields.io/badge/GitHub-FitzanAsh-181717?style=flat-square&logo=github)](https://github.com/FitzanAsh)

---

<div align="center">
  <b>⭐ Jangan lupa beri bintang jika project ini bermanfaat! ⭐</b>
</div>
