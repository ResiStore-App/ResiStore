<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Transaksi Penjualan')
                ->url(fn () => static::getResource()::getUrl('create-penjualan'))
                ->button(),

            Actions\Action::make('Transaksi Pembelian')
                ->url(fn () => static::getResource()::getUrl('create-pembelian'))
                ->color('success')
                ->button(),
        ];
    }
}
