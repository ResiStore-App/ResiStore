<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyTransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\{
    Card,
    DatePicker,
    Hidden,
    Repeater,
    Select,
    TextInput,
    Toggle
};
use Filament\Tables;
use Filament\Tables\Table;

class BuyTransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    protected static ?string $navigationLabel = 'Transaksi Pembelian';
    protected static ?string $label = 'Transaksi Pembelian';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                Hidden::make('jenis_transaksi')->default('pembelian'),

                DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->required(),

                Select::make('user_id')
                    ->label('Kasir / Petugas')
                    ->relationship('user', 'name')
                    ->required(),
            ])->columns(2),

            Card::make([
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
                            ->options(fn () => Product::pluck('nama_barang', 'id'))
                            ->searchable()
                            ->visible(fn (Get $get) => !$get('is_new'))
                            ->required(fn (Get $get) => !$get('is_new')),

                        TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->visible(fn (Get $get) => $get('is_new'))
                            ->required(fn (Get $get) => $get('is_new')),

                        TextInput::make('kategori')
                            ->label('Kategori')
                            ->visible(fn (Get $get) => $get('is_new'))
                            ->required(fn (Get $get) => $get('is_new')),

                        TextInput::make('satuan')
                            ->label('Satuan')
                            ->visible(fn (Get $get) => $get('is_new'))
                            ->required(fn (Get $get) => $get('is_new')),

                        TextInput::make('harga_jual')
                            ->label('Harga Jual')
                            ->numeric()
                            ->visible(fn (Get $get) => $get('is_new'))
                            ->required(fn (Get $get) => $get('is_new')),

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
}
