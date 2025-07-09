<?php

namespace App\Filament\Resources\BuyTransactionResource\Pages;

use App\Filament\Resources\BuyTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuyTransaction extends EditRecord
{
    protected static string $resource = BuyTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return BuyTransactionResource::getUrl('index');
    }
}
