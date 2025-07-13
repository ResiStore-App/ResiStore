<?php

namespace App\Filament\Resources;

use App\Filament\Exports\FinanceExporter;
use App\Filament\Resources\FinanceResource\Pages;
use App\Models\Finance;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms\Components\{
  DatePicker,
  Hidden,
  Select,
  TextInput
};
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\{
  DeleteAction,
  DeleteBulkAction,
  EditAction,
  ExportBulkAction
};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Column;

Column::configureUsing(function (Column $column): void {
  $column
    ->toggleable()
    ->sortable();
});

class FinanceResource extends Resource
{
  protected static ?string $model = Finance::class;

  protected static ?string $navigationIcon = 'heroicon-o-banknotes';
  protected static ?string $navigationGroup = 'Laporan';
  protected static ?string $navigationLabel = "Keuangan";

  public static function canCreate(): bool
  {
    return in_array(auth()->user()->role, ['admin', 'keuangan']);
  }

  public static function canEdit($record): bool
  {
    return in_array(auth()->user()->role, ['admin', 'keuangan']);
  }

  public static function canDelete($record): bool
  {
    return in_array(auth()->user()->role, ['admin', 'keuangan']);
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        DatePicker::make('tanggal')
          ->label('Tanggal')
          ->required()
          ->default(now())
          ->maxDate(now()),

        TextInput::make('keterangan')
          ->label('Keterangan')
          ->placeholder('Masukkan keterangan')
          ->required()
          ->maxLength(255),

        TextInput::make('nominal')
          ->label('Nominal')
          ->placeholder('Masukkan nominal')
          ->numeric()
          ->required()
          ->prefix('Rp'),

        Select::make('jenis')
          ->label('Jenis')
          ->placeholder('-- Pilih jenis --')
          ->options([
            'pemasukan' => 'Pemasukan',
            'pengeluaran' => 'Pengeluaran',
          ])
          ->required(),

        Hidden::make('user_id')
          ->default(fn() => auth()->id()),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->recordAction(null)
      ->emptyStateHeading('Tidak ada data keuangan yang ditemukan')
      ->emptyStateDescription('Silakan tambahkan data keuangan baru untuk memulai.')
      ->emptyStateIcon('heroicon-o-document')
      ->columns([
        TextColumn::make('id')
          ->label('ID'),

        TextColumn::make('user.name')
          ->label('User')
          ->searchable()
          ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('tanggal')
          ->label('Tanggal')
          ->date('d-m-Y'),

        TextColumn::make('keterangan')
          ->label('Keterangan')
          ->searchable(),

        TextColumn::make('jenis')
          ->label('Jenis')
          ->badge()
          ->color(fn(string $state): string => match ($state) {
            'pemasukan' => 'success',
            'pengeluaran' => 'danger',
          }),

        TextColumn::make('nominal')
          ->label('Nominal')
          ->prefix('Rp ')
          ->numeric(decimalPlaces: 0),

        TextColumn::make('created_at')
          ->label('Dibuat Pada')
          ->date('d-m-Y')
          ->toggleable(isToggledHiddenByDefault: true),

        TextColumn::make('updated_at')
          ->label('Diperbarui Pada')
          ->since()
          ->toggleable(isToggledHiddenByDefault: true),
      ])
      ->filters([
        Filter::make('filter_keuangan')
          ->form([
            DatePicker::make('from')
              ->label('Dari tanggal'),

            DatePicker::make('until')
              ->label('Sampai tanggal'),

            Select::make('jenis')
              ->label('Jenis')
              ->placeholder('-- Pilih Jenis --')
              ->options([
                'pemasukan' => 'Pemasukan',
                'pengeluaran' => 'Pengeluaran',
              ]),
          ])
          ->query(function (Builder $query, array $data) {
            return $query
              ->when($data['from'], fn($q) => $q->whereDate('tanggal', '>=', $data['from']))
              ->when($data['until'], fn($q) => $q->whereDate('tanggal', '<=', $data['until']))
              ->when($data['jenis'], fn($q) => $q->where('jenis', $data['jenis']));
          })
          ->indicateUsing(function (array $data) {
            $indicators = [];

            if (!empty($data['from'])) {
              $indicators[] = 'Dari: ' . $data['from'];
            }

            if (!empty($data['until'])) {
              $indicators[] = 'Sampai: ' . $data['until'];
            }

            if (!empty($data['jenis'])) {
              $indicators[] = 'Jenis: ' . ucfirst($data['jenis']);
            }

            return $indicators;
          }),
      ])
      ->actions([
        EditAction::make()
          ->color('warning')
          ->iconButton()
          ->modalHeading('Edit Data Keuangan')
          ->modalSubmitActionLabel('Simpan')
          ->modalIcon('heroicon-o-pencil-square')
          ->successNotification(
            Notification::make()
              ->success()
              ->title('Berhasil Edit')
              ->body('Data keuangan telah berhasil diperbarui.')
          ),

        DeleteAction::make()
          ->color('danger')
          ->iconButton()
          ->modalHeading('Hapus Data Keuangan')
          ->modalDescription('Apakah Anda yakin ingin menghapus data keuangan ini?')
          ->modalSubmitActionLabel('Hapus')
          ->modalIcon('heroicon-o-document-minus')
          ->successNotification(
            Notification::make()
              ->success()
              ->title('Berhasil Hapus')
              ->body('Data telah berhasil dihapus.')
          ),
      ])
      ->bulkActions([
        DeleteBulkAction::make()
          ->modalHeading('Hapus Data Keuangan yang dipilih')
          ->modalDescription('Apakah Anda yakin ingin menghapus data keuangan yang dipilih?')
          ->modalSubmitActionLabel('Hapus')
          ->successNotification(
            Notification::make()
              ->success()
              ->title('Berhasil Hapus')
              ->body('Data keuangan yang dipilih berhasil dihapus.')
          ),

        ExportBulkAction::make()
          ->exporter(FinanceExporter::class)
          ->fileName(fn(Export $export): string => "data-keuangan-{$export->getKey()}")
          ->label('Ekspor Data Keuangan yang dipilih')
          ->icon('heroicon-o-printer')
          ->color('success')
          ->modalHeading('Ekspor Data Keuangan yang dipilih')
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
      'index' => Pages\ListFinances::route('/'),
    ];
  }
}
