<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-user-group';
  protected static ?string $navigationGroup = 'Manajemen';
  protected static ?string $navigationLabel = 'User';

  public static function canCreate(): bool
  {
    return in_array(Auth::user()?->role, ['admin']);
  }

  public static function canEdit($record): bool
  {
    return in_array(Auth::user()?->role, ['admin']);
  }

  public static function canDelete($record): bool
  {
    return in_array(Auth::user()?->role, ['admin']);
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('name')
          ->label('Nama Pengguna')
          ->placeholder('Masukkan nama user')
          ->required()
          ->maxLength(50),
        TextInput::make('email')
          ->label('Email')
          ->placeholder('Masukkan email user')
          ->required()
          ->email()
          ->maxLength(100),
        TextInput::make('password')
          ->label('Kata Sandi')
          ->placeholder('Masukkan kata sandi user')
          ->required()
          ->password()
          ->minLength(6)
          ->maxLength(255)
          ->dehydrateStateUsing(fn($state) => bcrypt($state))
          ->visibleOn('create'),
        Select::make('role')
          ->label('Role')
          ->placeholder('-- Pilih Role User --')
          ->required()
          ->searchable()
          ->options([
            'admin' => 'Admin',
            'cs' => 'Kasir',
            'gudang' => 'Gudang',
            'keuangan' => 'Keuangan',
          ]),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('name')
          ->label('Nama Pengguna')
          ->searchable()
          ->sortable(),
        TextColumn::make('email')
          ->label('Email')
          ->searchable()
          ->sortable(),
        TextColumn::make('role')
          ->label('Role')
          ->searchable()
          ->toggleable()
          ->sortable()
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'admin' => 'danger',
            'cs' => 'success',
            'gudang' => 'info',
            'keuangan' => 'warning',
            default => 'gray',
          })
          ->formatStateUsing(fn(string $state) => match ($state) {
            'admin' => 'Admin',
            'cs' => 'Kasir',
            'gudang' => 'Gudang',
            'keuangan' => 'Keuangan',
            default => ucfirst($state),
          }),
        TextColumn::make('created_at')
          ->label('Dibuat Pada')
          ->formatStateUsing(fn($state) => $state->format('d-m-Y')),
      ])
      ->filters([
        //
      ])
      ->actions([
        EditAction::make()
          ->color('warning')
          ->iconButton()
          ->modalHeading('Edit User')
          ->modalSubmitActionLabel('Simpan')
          ->modalIcon('heroicon-o-pencil-square')
          ->successNotification(
            Notification::make()
              ->success()
              ->title('Berhasil Edit')
              ->body('User telah berhasil diperbarui.')
          ),
        DeleteAction::make()
          ->modalHeading('Hapus User')
          ->modalDescription('Apakah Anda yakin ingin menghapus user ini?')
          ->modalSubmitActionLabel('Hapus')
          ->modalIcon('heroicon-o-user-minus')
          ->successNotification(
            Notification::make()
              ->success()
              ->title('Berhasil Hapus')
              ->body('User telah berhasil dihapus.')
          )
          ->iconButton(),
      ])
      ->bulkActions([
        DeleteBulkAction::make()
          ->modalHeading('Hapus User yang dipilih')
          ->modalDescription('Apakah Anda yakin ingin menghapus user yang dipilih?')
          ->modalSubmitActionLabel('Hapus')
          ->successNotificationTitle('Berhasil!')
          ->icon('heroicon-o-user-minus'),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListUsers::route('/'),
    ];
  }
}
