<?php

namespace App\Filament\Resources\SellTransactionResource\Pages;

use App\Filament\Resources\SellTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSellTransactions extends ListRecords
{
    protected static string $resource = SellTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Tambah Transaksi Penjualan'),
        ];
    }
}
