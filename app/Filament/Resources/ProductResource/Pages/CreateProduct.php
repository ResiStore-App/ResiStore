<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
  protected static string $resource = ProductResource::class;

  protected static ?string $title = 'Tambah Barang';
  protected static ?string $breadcrumb = 'Tambah Barang';

  protected function getRedirectUrl(): string
  {
    return $this->getResource()::getUrl('index');
  }

  protected function getCreatedNotification(): ?Notification
  {
    return Notification::make()
      ->success()
      ->title('Berhasil Ditambahkan')
      ->body('Barang baru telah berhasil ditambahkan ke dalam sistem.');
  }
}
