<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
  protected static string $resource = ProductResource::class;

  protected static ?string $title = 'Daftar Barang';

  protected function getHeaderActions(): array
  {
    return [
      CreateAction::make()
        ->label('Tambah Barang')
        ->icon('heroicon-o-squares-plus')
        ->color('primary')
        ->modalHeading('Tambah Barang')
        ->modalSubmitActionLabel('Tambah')
        ->modalIcon('heroicon-o-squares-plus')
        ->createAnother(false)
        ->successNotification(
          Notification::make()
            ->success()
            ->title('Berhasil Tambah')
            ->body('Barang baru telah berhasil ditambahkan.')
        ),
    ];
  }
}
