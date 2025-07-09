<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyTransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Forms\Components\{
    DatePicker,
    Hidden,
    Repeater,
    Select,
    TextInput,
    Toggle
};
use Filament\Forms\Get;
use Filament\Resources\Resource;
use App\Models\Product;
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
        Hidden::make('jenis_transaksi')->default('pembelian'),

        DatePicker::make('tanggal_transaksi')
            ->required()
            ->label('Tanggal Transaksi'),

        Select::make('user_id')
            ->label('Kasir / Petugas')
            ->relationship('user', 'name')
            ->required(),

        Repeater::make('details')
            ->label('Detail Barang')
            ->schema([
                Hidden::make('id_barang')->dehydrated(),

                Toggle::make('is_new')
                    ->label('Barang Baru?')
                    ->default(false)
                    ->reactive()
                    ->inline(),

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
                    ->visible(fn (Get $get) => $get('is_new'))
                    ->required(fn (Get $get) => $get('is_new')),

                TextInput::make('satuan')
                    ->visible(fn (Get $get) => $get('is_new'))
                    ->required(fn (Get $get) => $get('is_new')),

                TextInput::make('harga_jual')
                    ->label('Harga Jual')
                    ->numeric()
                    ->visible(fn (Get $get) => $get('is_new'))
                    ->required(fn (Get $get) => $get('is_new')),

                TextInput::make('kuantitas')
                    ->numeric()
                    ->required()
                    ->label('Jumlah'),

                TextInput::make('harga_satuan')
                    ->numeric()
                    ->required()
                    ->label('Harga Satuan'),
            ])
            ->columns(2)
            ->required(),
    ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_transaksi')->date('d M Y'),
                Tables\Columns\TextColumn::make('user.name')->label('Kasir'),
                Tables\Columns\TextColumn::make('total_harga')->money('IDR')->label('Total Harga'),
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
            'index' => Pages\ListBuyTransactions::route('/'),
            'create' => Pages\CreateBuyTransaction::route('/create'),
            'edit' => Pages\EditBuyTransaction::route('/{record}/edit'),
            'view' => Pages\ViewBuyTransaction::route('/{record}'),
        ];
    }
}
