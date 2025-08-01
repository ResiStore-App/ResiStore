<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
  protected static string $resource = UserResource::class;

  protected static ?string $title = 'Data User';

  protected function getHeaderActions(): array
  {
    return [
      CreateAction::make()
        ->label('Tambah User')
        ->icon('heroicon-o-user-plus')
        ->color('primary')
        ->modalHeading('Tambah User')
        ->modalSubmitActionLabel('Tambah')
        ->modalIcon('heroicon-o-user-plus')
        ->createAnother(false)
        ->successNotification(
          Notification::make()
            ->success()
            ->title('Berhasil Simpan')
            ->body('User baru telah berhasil ditambahkan.')
        ),
    ];
  }
}
