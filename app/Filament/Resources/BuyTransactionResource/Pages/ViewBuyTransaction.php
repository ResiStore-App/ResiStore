<?php

namespace App\Filament\Resources\BuyTransactionResource\Pages;

use App\Filament\Resources\BuyTransactionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBuyTransaction extends ViewRecord
{
    protected static string $resource = BuyTransactionResource::class;

    public function getView(): string
    {
        return 'filament.resources.buy-transaction-resource.pages.view-buy-transaction';
    }

    public function getViewData(): array
    {
        return [
            'details' => $this->record->details()->with('barang')->get(),
        ];
    }
}
