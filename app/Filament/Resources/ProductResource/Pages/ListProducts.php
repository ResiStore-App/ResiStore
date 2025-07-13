<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Exports\ProductExporter;
use App\Filament\Resources\ProductResource;
use Filament\Actions\{
  CreateAction,
  ExportAction
};
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
  protected static string $resource = ProductResource::class;

  protected static ?string $title = 'Data Barang';

  protected function getHeaderActions(): array
  {
    return [
      ExportAction::make()
        ->exporter(ProductExporter::class)
        ->fileName(fn(Export $export): string => "semua-data-barang-{$export->getKey()}")
        ->label('Ekspor Barang')
        ->icon('heroicon-o-printer')
        ->color('success')
        ->modalHeading('Ekspor Semua Data Barang'),
        
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
            ->title('Berhasil Simpan')
            ->body('Barang baru telah berhasil ditambahkan.')
        ),
    ];
  }
}
