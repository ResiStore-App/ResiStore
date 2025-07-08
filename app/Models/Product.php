<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
  use HasFactory;

  protected $table = 'tb_barang';

  protected $fillable = [
    'nama_barang',
    'kategori',
    'stok',
    'satuan',
    'harga_beli',
    'harga_jual',
  ];

  public function detailTransaksi()
  {
    return $this->hasMany(DetailTransaction::class, 'barang_id');
  }
}
