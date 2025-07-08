<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTransaction extends Model
{
    use HasFactory;

    protected $table = 'tb_detail_transaksi';

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'jumlah',
        'subtotal',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaction::class, 'transaksi_id');
    }

    public function barang()
    {
        return $this->belongsTo(Product::class, 'barang_id');
    }
}
