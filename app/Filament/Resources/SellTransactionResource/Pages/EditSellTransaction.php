<?php

namespace App\Filament\Resources\SellTransactionResource\Pages;

use App\Filament\Resources\SellTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSellTransaction extends EditRecord
{
    protected static string $resource = SellTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
