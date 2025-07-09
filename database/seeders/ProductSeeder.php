<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'nama_barang' => 'Arduino Uno R3',
                'kategori' => 'komponen',
                'stok' => 20,
                'satuan' => 'pcs',
                'harga_beli' => 75000,
                'harga_jual' => 95000,
            ],
            [
                'nama_barang' => 'Kabel Jumper Male to Male 20cm (40 pcs)',
                'kategori' => 'aksesoris',
                'stok' => 100,
                'satuan' => 'set',
                'harga_beli' => 15000,
                'harga_jual' => 25000,
            ],
            [
                'nama_barang' => 'Sensor Suhu DHT11',
                'kategori' => 'komponen',
                'stok' => 60,
                'satuan' => 'pcs',
                'harga_beli' => 10000,
                'harga_jual' => 18000,
            ],
            [
                'nama_barang' => 'Relay 5V 1 Channel',
                'kategori' => 'komponen',
                'stok' => 45,
                'satuan' => 'pcs',
                'harga_beli' => 8000,
                'harga_jual' => 15000,
            ],
            [
                'nama_barang' => 'Breadboard 830 Points',
                'kategori' => 'aksesoris',
                'stok' => 35,
                'satuan' => 'pcs',
                'harga_beli' => 20000,
                'harga_jual' => 30000,
            ],
            [
                'nama_barang' => 'Power Supply 9V DC Adapter',
                'kategori' => 'elektronik_rumah',
                'stok' => 25,
                'satuan' => 'pcs',
                'harga_beli' => 18000,
                'harga_jual' => 28000,
            ],
            [
                'nama_barang' => 'ESP32 DevKit V1',
                'kategori' => 'komponen',
                'stok' => 15,
                'satuan' => 'pcs',
                'harga_beli' => 40000,
                'harga_jual' => 60000,
            ],
        ];

        foreach($products as $product) {
            Product::create($product);
        }

    }
}
