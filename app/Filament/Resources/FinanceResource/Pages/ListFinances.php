<?php

namespace App\Filament\Resources\FinanceResource\Pages;

use App\Filament\Exports\FinanceExporter;
use App\Filament\Resources\FinanceResource;
use App\Filament\Resources\FinanceResource\Widgets\FinanceOverview;
use Filament\Actions\{
  CreateAction,
  ExportAction
};
use Filament\Actions\Exports\Models\Export;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListFinances extends ListRecords
{
  protected static string $resource = FinanceResource::class;

  protected static ?string $title = 'Data Keuangan';

  protected function getHeaderWidgets(): array
  {
    return [
      FinanceOverview::class,
    ];
  }

  protected function getHeaderActions(): array
  {
    return [
      ExportAction::make()
        ->exporter(FinanceExporter::class)
        ->fileName(fn(Export $export): string => "semua-data-keuangan-{$export->getKey()}")
        ->label('Ekspor Data Keuangan')
        ->icon('heroicon-o-printer')
        ->color('success')
        ->modalHeading('Ekspor Semua Data Keuangan'),
        
      CreateAction::make()
        ->label('Tambah Data Keuangan')
        ->icon('heroicon-o-document-plus')
        ->color('primary')
        ->modalHeading('Tambah Data Keuangan')
        ->modalSubmitActionLabel('Tambah')
        ->modalIcon('heroicon-o-document-plus')
        ->createAnother(false)
        ->successNotification(
          Notification::make()
            ->success()
            ->title('Berhasil Tambah')
            ->body('Data keuangan telah berhasil ditambahkan.')
        ),
    ];
  }
}
