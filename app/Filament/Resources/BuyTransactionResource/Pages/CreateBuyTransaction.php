<?php

namespace App\Filament\Resources\BuyTransactionResource\Pages;

use App\Filament\Resources\BuyTransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBuyTransaction extends CreateRecord
{
    protected static string $resource = BuyTransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;

        foreach ($data['details'] as &$detail) {
            $qty = $detail['kuantitas'];
            $price = $detail['harga_satuan'];
            $total += $qty * $price;

            if (!empty($detail['is_new'])) {
                $product = Product::create([
                    'nama_barang' => $detail['nama_barang'],
                    'kategori' => $detail['kategori'],
                    'satuan' => $detail['satuan'],
                    'harga_jual' => $detail['harga_jual'],
                    'harga_beli' => $price,
                    'stok' => $qty,
                ]);

                $detail['id_barang'] = $product->id;
            } else {
                Product::where('id', $detail['id_barang'])->increment('stok', $qty);
            }
        }

        $data['total_harga'] = $total;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $details = $data['details'];
        unset($data['details']);

        $transaction = Transaction::create($data);

        foreach ($details as $detail) {
            DetailTransaction::create([
                'id_transaksi' => $transaction->id,
                'id_barang' => $detail['id_barang'],
                'kuantitas' => $detail['kuantitas'],
                'harga_satuan' => $detail['harga_satuan'],
            ]);
        }

        return $transaction;
    }
}
