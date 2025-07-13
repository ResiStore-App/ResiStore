<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'tb_transaksi';

    protected $fillable = [
        'tanggal_transaksi',
        'jenis_transaksi',
        'total_harga',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaction::class, 'id_transaksi');
    }

    protected static function booted()
    {
        static::created(function ($transaksi) {
            \App\Models\Finance::create([
                'tanggal' => $transaksi->tanggal_transaksi,
                'keterangan' => ucfirst($transaksi->jenis_transaksi) . ' ID #' . $transaksi->id,
                'jenis' => $transaksi->jenis_transaksi === 'penjualan' ? 'pemasukan' : 'pengeluaran',
                'nominal' => $transaksi->total_harga,
                'user_id' => auth()->id(),
            ]);
        });
    }
}
