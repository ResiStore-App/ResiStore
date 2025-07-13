# ResiStore
    
### ResiStore adalah aplikasi berbasis Laravel 12 + Filament V3.3 untuk membantu pengelolaan **inventori dan keuangan toko elektronik** secara modern, cepat, dan efisien.

## 🚀 Fitur Utama

- **Dashboard Utama**

    Statistik & Widget total stok, transaksi, keuangan.

- **Manajemen Barang**
    
    Tambah, edit, hapus barang seperti komputer, laptop, TV, dan lainnya.

- **Manajemen User**
    
    Tambah, edit, hapus user oleh admin.

- **Profil**

    Edit akun sendiri.

- **Transaksi**

    Pencatatan transaksi pembelian dan penjualan.

- **Keuangan**

    Mencatat pemasukan dan pengeluaran dengan grafik dan ekspor data.

- **Hak Akses User (Role)**

    Admin, Gudang, Kasir (CS), Keuangan.

- **Ekspor Data**

    - Ekspor semua data sekaligus.
    - Ekspor data tertentu yang dipilih.
    - Ekspor data ke file Excel dan CSV.

- **Kolom Tabel bisa Kustom**

    Pilih kolom yang ingin ditampilkan/sembunyikan sesuai kebutuhan.

- **Tabel bisa di Filter**

    Pilih filter sesuai kebutuhan.

## 🛠️ Instalasi

1. **Clone the Repository:**

    ```shell
    git clone https://github.com/ResiStore-App/ResiStore.git
    cd resistore
    ```

2. **Copy `.env` File & Generate App Key**

    ```shell
    cp .env.example .env
    php artisan key:generate
    ```

3. **Update `.env` File**

    Edit the following environment variables as needed:

    ```shell
    DB_CONNECTION=mysql
    DB_DATABASE=db_resistore
    DB_USERNAME=root
    DB_PASSWORD=
    APP_URL=http://localhost
    QUEUE_CONNECTION=sync
    ```

4. **Install Dependencies:**

    ```shell
    composer install
    npm install
    npm run build
    ```

5. **Ensure MySQL Server is Running**

    Make sure your MySQL server is running before proceeding.

6. **Run Database Migration:**

    ```shell
    php artisan migrate
    ```

    If you want to erase all previous data and start from zero:

    ```shell
    php artisan migrate:fresh
    ```

    or if you want to reset and seeder at the same time (skip step 7):

    ```shell
    php artisan migrate:fresh --seed
    ```

7. **Run database seeder (Optional but Recommended):**

    ```shell
    php artisan db:seed
    ```

8. **Create Admin User for Filament Panel:**

    ```shell
    php artisan make:filament-user
    ```

9. **Run the Application:**

    ```shell
    php artisan serve
    ```
## 👤 Hak Akses

| Role     | Akses                                      |
|----------|--------------------------------------------|
| Admin    | Semua fitur dan data                       |
| Gudang   | Kelola barang                              |
| Kasir    | Transaksi penjualan/pembelian              |
| Keuangan | Laporan & pencatatan keuangan              |

## 🔐 Keamanan & Validasi

| Aspek                    | Status | Catatan Singkat                                     |
| ------------------------ | ------ | --------------------------------------------------- |
| Role-based Access        | ✅ | Sudah dibatasi per-role pada resource tertentu          |
| Validasi Form            | ✅ | Validasi via Form Schema Filament                       |
| CSRF Protection          | ✅ | Bawaan Laravel & Filament                               |
| SQL Injection Protection | ✅ | Tidak ada raw SQL, semua pakai ORM                      |
| Ekspor/Backup            | ✅ | Data Barang dan Keuangan bisa di ekspor ke .xlsx & .csv |

### 📄 Lisensi

MIT. Bebas digunakan untuk pembelajaran atau referensi.
