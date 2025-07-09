<?php

namespace App\Filament\Resources\BuyTransactionResource\Pages;

use App\Filament\Resources\BuyTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuyTransaction extends EditRecord
{
    protected static string $resource = BuyTransactionResource::class;
    protected function mutateFormDataBeforeFill(array $data): array
    {

        $details = $this->record->details()->get()->map(function ($item) {
            return [
                'id_barang' => $item->id_barang,
                'kuantitas' => $item->kuantitas,
                'harga_satuan' => $item->harga_satuan,
                'is_new' => false,
            ];
        })->toArray();

        $data['details'] = $details;

        return $data;
    }
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
