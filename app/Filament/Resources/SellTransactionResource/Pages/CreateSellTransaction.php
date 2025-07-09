<?php

namespace App\Filament\Resources\SellTransactionResource\Pages;

use App\Filament\Resources\SellTransactionResource;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\DetailTransaction;
use Filament\Resources\Pages\CreateRecord;

class CreateSellTransaction extends CreateRecord
{
    protected static string $resource = SellTransactionResource::class;

   protected function mutateFormDataBeforeCreate(array $data): array
    {
        $total = 0;

        foreach ($data['details'] as &$detail) {
            $qty = $detail['kuantitas'];
            $product = Product::find($detail['id_barang']);
            $price = $product?->harga_jual ?? 0;

            $detail['harga_satuan'] = $price;
            $total += $qty * $price;

            Product::where('id', $detail['id_barang'])->decrement('stok', $qty);
        }

        $data['total_harga'] = $total;

        return $data;
    }

    protected function handleRecordCreation(array $data): Transaction
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
