<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\{
  CheckboxList,
  Select,
  TextInput
};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\{
  DeleteAction,
  DeleteBulkAction,
  EditAction
};
use Filament\Tables\Columns\{
  TextColumn,
  Column
};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

Column::configureUsing(function (Column $column): void {
  $column
    ->toggleable()
    ->sortable();
});

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-user-group';
  protected static ?string $navigationGroup = 'Manajemen';
  protected static ?string $navigationLabel = 'User';

  public static function canCreate(): bool
  {
    return in_array(auth()->user()->role, ['admin']);
  }

  public static function canEdit($record): bool
  {
    return in_array(auth()->user()->role, ['admin']);
  }

  public static function canDelete($record): bool
  {
    return in_array(auth()->user()->role, ['admin']);
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        TextInput::make('name')
          ->label('Nama User')
          ->placeholder('Masukkan nama user')
          ->required()
          ->maxLength(50),

        TextInput::make('email')
          ->label('Email')
          ->placeholder('Masukkan email user')
          ->required()
          ->email()
          ->maxLength(100)
          ->prefixIcon('heroicon-m-envelope'),

        TextInput::make('password')
          ->label('Kata Sandi')
          ->placeholder('Masukkan kata sandi user')
          ->required()
          ->password()
          ->minLength(6)
          ->maxLength(255)
          ->revealable()
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
      ->recordAction(null)
      ->columns([
        TextColumn::make('id')
          ->label('ID'),

        TextColumn::make('name')
          ->label('Nama Pengguna')
          ->searchable(),

        TextColumn::make('email')
          ->label('Email')
          ->icon('heroicon-m-envelope')
          ->fontFamily(FontFamily::Mono)
          ->searchable()
          ->copyable()
          ->copyMessage('Email disalin ke clipboard')
          ->copyMessageDuration(1500),

        TextColumn::make('role')
          ->label('Role')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'admin' => 'danger',
            'cs' => 'success',
            'gudang' => 'info',
            'keuangan' => 'warning',
          })
          ->formatStateUsing(fn(string $state) => match ($state) {
            'admin' => 'Admin',
            'cs' => 'Kasir',
            'gudang' => 'Gudang',
            'keuangan' => 'Keuangan',
          }),

        TextColumn::make('created_at')
          ->label('Dibuat Pada')
          ->date('d-m-Y'),

        TextColumn::make('updated_at')
          ->label('Diperbarui Pada')
          ->since()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Filter::make('roles')
          ->form([
            CheckboxList::make('roles')
              ->label('Filter Role')
              ->options([
                'admin' => 'Admin',
                'gudang' => 'Gudang',
                'kasir' => 'Kasir',
                'keuangan' => 'Keuangan',
              ]),
          ])
          ->query(function (Builder $query, array $data) {
            return $query->when(
              $data['roles'],
              fn($q, $roles) => $q->whereIn('role', $roles)
            );
          })
          ->indicateUsing(function (array $data) {
            return collect($data['roles'] ?? [])
              ->map(fn($role) => 'Role: ' . ucfirst($role))
              ->toArray();
          }),
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
              ->title('Berhasil Simpan')
              ->body('User telah berhasil diperbarui.')
          ),
          
        DeleteAction::make()
          ->color('danger')
          ->iconButton()
          ->modalHeading('Hapus User')
          ->modalDescription('Apakah Anda yakin ingin menghapus user ini?')
          ->modalSubmitActionLabel('Hapus')
          ->modalIcon('heroicon-o-user-minus')
          ->successNotification(
            Notification::make()
              ->success()
              ->title('Berhasil Hapus')
              ->body('User telah berhasil dihapus.')
          ),
      ])
      ->bulkActions([
        DeleteBulkAction::make()
          ->modalHeading('Hapus User yang dipilih')
          ->modalDescription('Apakah Anda yakin ingin menghapus user yang dipilih?')
          ->modalSubmitActionLabel('Hapus')
          ->successNotification(
            Notification::make()
              ->success()
              ->title('Berhasil Hapus')
              ->body('User yang dipilih berhasil dihapus.')
          ),
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
