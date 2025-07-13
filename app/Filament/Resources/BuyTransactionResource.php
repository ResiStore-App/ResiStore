<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyTransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\{
  DatePicker,
  Hidden,
  Repeater,
  Section,
  Select,
  TextInput,
  Toggle
};
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BuyTransactionResource extends Resource
{
  protected static ?string $model = Transaction::class;
  protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
  protected static ?string $navigationGroup = 'Transaksi';
  protected static ?string $navigationLabel = 'Pembelian';
  protected static ?string $label = 'Pembelian';

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
        Hidden::make('jenis_transaksi')->default('pembelian'),

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
            Toggle::make('is_new')
              ->label('Barang Baru?')
              ->default(false)
              ->inline()
              ->reactive(),

            Select::make('id_barang')
              ->label('Pilih Barang')
              ->options(fn() => Product::pluck('nama_barang', 'id'))
              ->searchable()
              ->visible(fn(Get $get) => !$get('is_new'))
              ->required(fn(Get $get) => !$get('is_new')),

            TextInput::make('nama_barang')
              ->label('Nama Barang')
              ->visible(fn(Get $get) => $get('is_new'))
              ->required(fn(Get $get) => $get('is_new')),

            Select::make('kategori')
              ->label('Kategori')
              ->placeholder('-- Pilih Kategori Barang --')
              ->searchable()
              ->options([
                'komputer' => 'Komputer & Laptop',
                'monitor' => 'Monitor & Display',
                'printer' => 'Printer & Scanner',
                'jaringan' => 'Peralatan Jaringan',
                'aksesoris' => 'Aksesoris & Kabel',
                'penyimpanan' => 'Media Penyimpanan',
                'audio' => 'Audio & Mic',
                'elektronik_rumah' => 'Elektronik Rumah Tangga',
                'keamanan' => 'CCTV & Keamanan',
                'smart_home' => 'Smart Home & Otomasi',
                'komponen' => 'Komponen Elektronik & Robotik',
                'software' => 'Software & Lisensi',
                'servis' => 'Jasa Servis',
                'lainnya' => 'Lain-lain',
              ])
              ->visible(fn(Get $get) => $get('is_new'))
              ->required(fn(Get $get) => $get('is_new')),

            Select::make('satuan')
              ->label('Satuan')
              ->placeholder('-- Pilih Satuan Barang --')
              ->searchable()
              ->options([
                'pcs' => 'Pcs',   // Untuk barang eceran, kecil
                'unit' => 'Unit', // Untuk barang besar/formal
                'box' => 'Box',   // Untuk kemasan kotak/paket
                'set' => 'Set',   // Untuk barang yang dijual dalam set
              ])
              ->visible(fn(Get $get) => $get('is_new'))
              ->required(fn(Get $get) => $get('is_new')),

            TextInput::make('harga_jual')
              ->label('Harga Jual')
              ->numeric()
              ->visible(fn(Get $get) => $get('is_new'))
              ->required(fn(Get $get) => $get('is_new')),

            TextInput::make('kuantitas')
              ->label('Jumlah')
              ->numeric()
              ->required(),

            TextInput::make('harga_satuan')
              ->label('Harga Satuan')
              ->numeric()
              ->required(),

            Hidden::make('id_barang')
              ->dehydrated(),
          ])
          ->columns(3)
          ->addActionLabel('Tambah Barang')
          ->required(),
      ]),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->emptyStateHeading('Tidak ada riwayat pembelian yang ditemukan')
      ->emptyStateDescription('Silakan tambahkan riwayat pembelian baru untuk memulai.')
      ->emptyStateIcon('heroicon-o-archive-box-x-mark')
      ->columns([
        Tables\Columns\TextColumn::make('tanggal_transaksi')
          ->label('Tanggal Transaksi')
          ->date('d M Y'),

        Tables\Columns\TextColumn::make('user.name')
          ->label('Kasir'),

        Tables\Columns\TextColumn::make('total_harga')
          ->label('Total Harga')
          ->money('IDR'),
      ])
      ->actions([
        Tables\Actions\ViewAction::make()->label('Lihat'),
        Tables\Actions\EditAction::make()->label('Edit'),
        Tables\Actions\DeleteAction::make()->label('Hapus'),
      ])
      ->defaultSort('tanggal_transaksi', 'desc');
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListBuyTransactions::route('/'),
      'create' => Pages\CreateBuyTransaction::route('/create'),
      'edit' => Pages\EditBuyTransaction::route('/{record}/edit'),
      'view' => Pages\ViewBuyTransaction::route('/{record}'),
    ];
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()->where('jenis_transaksi', 'pembelian');
  }
}
