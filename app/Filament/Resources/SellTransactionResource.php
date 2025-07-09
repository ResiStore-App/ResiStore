<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellTransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Components\{
    Card,
    DatePicker,
    Select,
    TextInput,
    Toggle,
    Repeater,
    Hidden,
    Placeholder
};
use Filament\Tables;
use Filament\Tables\Table;

class SellTransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationLabel = 'Transaksi Penjualan';
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    protected static ?string $label = 'Transaksi Penjualan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Card::make([
                Hidden::make('jenis_transaksi')->default('penjualan'),

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
                        Hidden::make('id_barang')->dehydrated(),

                        Select::make('id_barang')
                            ->label('Pilih Barang')
                            ->options(fn () => Product::pluck('nama_barang', 'id'))
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
}
