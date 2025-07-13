<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellTransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Components\{
  DatePicker,
  Select,
  TextInput,
  Repeater,
  Hidden,
  Placeholder,
  Section
};
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SellTransactionResource extends Resource
{
  protected static ?string $model = Transaction::class;
  protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
  protected static ?string $navigationGroup = 'Transaksi';
  protected static ?string $navigationLabel = 'Penjualan';
  protected static ?string $label = 'Penjualan';

  public static function canCreate(): bool
  {
    return in_array(auth()->user()->role, ['admin', 'kasir']);
  }

  public static function canEdit($record): bool
  {
    return in_array(auth()->user()->role, ['admin', 'kasir']);
  }

  public static function canDelete($record): bool
  {
    return in_array(auth()->user()->role, ['admin', 'kasir']);
  }

  public static function form(Form $form): Form
  {
    return $form->schema([
      Section::make([
        Hidden::make('jenis_transaksi')->default('penjualan'),

        DatePicker::make('tanggal_transaksi')
          ->label('Tanggal Transaksi')
          ->required(),

        Select::make('user_id')
          ->label('Kasir / Petugas')
          ->relationship('user', 'name')
          ->required(),
      ])->columns(2),

      Section::make([
        Repeater::make('details')
          ->label('Detail Barang')
          ->schema([
            Hidden::make('id_barang')->dehydrated(),

            Select::make('id_barang')
              ->label('Pilih Barang')
              ->options(fn() => Product::pluck('nama_barang', 'id'))
              ->searchable()
              ->required()
              ->reactive()
              ->afterStateUpdated(function ($state, callable $set) {
                $product = Product::find($state);
                if ($product) {
                  $set('harga_satuan', $product->harga_jual);
                } else {
                  $set('harga_satuan', 0);
                }
              }),

            TextInput::make('kuantitas')
              ->numeric()
              ->required()
              ->label('Jumlah'),

            Placeholder::make('harga_satuan_display')
              ->label('Harga Satuan')
              ->content(function ($get) {
                $product = Product::find($get('id_barang'));
                return $product
                  ? 'Rp ' . number_format($product->harga_jual, 0, ',', '.')
                  : '-';
              }),

            Hidden::make('harga_satuan'),
          ])
          ->columns(3)
          ->required()
          ->addActionLabel('Tambah Barang')
      ]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->emptyStateHeading('Tidak ada riwayat penjualan yang ditemukan')
      ->emptyStateDescription('Silakan tambahkan riwayat penjualan baru untuk memulai.')
      ->emptyStateIcon('heroicon-o-shopping-cart')
      ->columns([
        Tables\Columns\TextColumn::make('tanggal_transaksi')->date(),
        Tables\Columns\TextColumn::make('user.name')->label('Kasir'),
        Tables\Columns\TextColumn::make('total_harga')->money('IDR'),
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->defaultSort('tanggal_transaksi', 'desc');
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListSellTransactions::route('/'),
      'create' => Pages\CreateSellTransaction::route('/create'),
      'edit' => Pages\EditSellTransaction::route('/{record}/edit'),
      'view' => Pages\ViewSellTransaction::route('/{record}'),
    ];
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()->where('jenis_transaksi', 'penjualan');
  }
}
