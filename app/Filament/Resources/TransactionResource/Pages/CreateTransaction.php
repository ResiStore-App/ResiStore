<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
        {
            $data['total_harga'] = 0;
            return $data;
        }
    protected function afterCreate(): void
    {
        $transaction = $this->record;

        $total = $transaction->details->sum(function ($item) {
            return $item->kuantitas * $item->harga_satuan;
        });

        $transaction->update([
            'total_harga' => $total,
        ]);

        Notification::make()
            ->title('Transaksi berhasil disimpan')
            ->success()
            ->send();
    }
}
