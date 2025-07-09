<?php

namespace App\Filament\Resources\BuyTransactionResource\Pages;

use App\Filament\Resources\BuyTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuyTransactions extends ListRecords
{
    protected static string $resource = BuyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Tambah Pembelian')
                ->label('Tambah Transaksi Pembelian')
                ->url(fn () => static::getResource()::getUrl('create'))
                ->color('success')
                ->button(),
        ];
    }
}
