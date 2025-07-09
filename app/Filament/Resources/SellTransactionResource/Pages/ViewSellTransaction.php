<?php

namespace App\Filament\Resources\SellTransactionResource\Pages;

use App\Filament\Resources\SellTransactionResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSellTransaction extends ViewRecord
{
    protected static string $resource = SellTransactionResource::class;

    public function getView(): string
    {
        return 'filament.resources.sell-transaction-resource.pages.view-sell-transaction';
    }
}
