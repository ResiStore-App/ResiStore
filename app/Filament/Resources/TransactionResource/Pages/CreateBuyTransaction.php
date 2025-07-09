<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBuyTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['jenis_transaksi'] = 'pembelian';

        $data['total_harga'] = collect($data['details'] ?? [])->sum(function ($item) {
            return $item['kuantitas'] * $item['harga_satuan'];
        });

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->record->details as $detail) {
            $barang = $detail->barang;
            $barang->stok += $detail->kuantitas;
            $barang->save();
        }
    }
}
