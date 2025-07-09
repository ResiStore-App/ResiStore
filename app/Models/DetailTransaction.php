<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransaction extends Model
{
    use HasFactory;

    protected $table = 'tb_detail_transaksi';

    protected $fillable = [
        'id_transaksi',
        'id_barang',
        'kuantitas',
        'harga_satuan',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class, 'id_transaksi');
    }

    public function barang()
    {
        return $this->belongsTo(Product::class, 'id_barang');
    }
}
