<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationGroup = 'Manajemen Transaksi';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?string $label = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('tanggal_transaksi')->required(),
            Forms\Components\Select::make('jenis_transaksi')
                ->options([
                    'penjualan' => 'Penjualan',
                    'pembelian' => 'Pembelian / Restock',
                ])->required(),

            Forms\Components\Select::make('user_id')
                ->label('Kasir / Petugas')
                ->relationship('user', 'name')
                ->required(),

            Repeater::make('details')
                ->label('Detail Barang')
                ->relationship('details')
                ->schema([
                    Forms\Components\Select::make('id_barang')
                        ->relationship('barang', 'nama_barang')
                        ->required(),

                    Forms\Components\TextInput::make('kuantitas')
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('harga_satuan')
                        ->numeric()
                        ->required(),
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
                Tables\Columns\TextColumn::make('tanggal_transaksi')->date(),
                Tables\Columns\TextColumn::make('jenis_transaksi')->badge(),
                Tables\Columns\TextColumn::make('user.nama')->label('Kasir'),
                Tables\Columns\TextColumn::make('total_harga')->money('IDR'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),   
                Tables\Actions\EditAction::make(),   
                Tables\Actions\DeleteAction::make(), 
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_transaksi')
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
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}  
