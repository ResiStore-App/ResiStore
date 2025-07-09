<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $label = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            DatePicker::make('tanggal_transaksi')
                ->required()
                ->label('Tanggal Transaksi'),

            Select::make('user_id')
                ->label('Kasir / Petugas')
                ->relationship('user', 'name')
                ->required(),

            Repeater::make('details')
                ->label('Detail Barang')
                ->relationship('details')
                ->schema([
                    Group::make([
                        Select::make('id_barang')
                            ->label('Barang')
                            ->relationship('barang', 'nama_barang')
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('nama_barang')->required(),
                                TextInput::make('kategori')->required(),
                                TextInput::make('satuan')->required(),
                                TextInput::make('stok')->numeric()->default(0)->label('Stok Awal'),
                                TextInput::make('harga_beli')->numeric()->required(),
                                TextInput::make('harga_jual')->numeric()->required(),
                            ])
                    ])->columns(1),

                    TextInput::make('kuantitas')
                        ->numeric()
                        ->required()
                        ->label('Jumlah'),

                    TextInput::make('harga_satuan')
                        ->numeric()
                        ->required()
                        ->label('Harga Satuan'),
                ])
                ->columns(3)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID Transaksi'),
                Tables\Columns\TextColumn::make('tanggal_transaksi')
                    ->label('Tanggal')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('jenis_transaksi')
                    ->label('Jenis')
                    ->badge(),
                Tables\Columns\TextColumn::make('user.nama')
                    ->label('Kasir'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->label('Total Harga')
                    ->money('IDR'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_transaksi')
                    ->label('Jenis Transaksi')
                    ->options([
                        'penjualan' => 'Penjualan',
                        'pembelian' => 'Pembelian',
                    ]),
            ])
            ->defaultSort('tanggal_transaksi', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create-penjualan' => Pages\CreateSellTransaction::route('/penjualan/create'),
            'create-pembelian' => Pages\CreateBuyTransaction::route('/pembelian/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
