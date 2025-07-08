<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
	protected static ?string $model = Product::class;

	protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
	protected static ?string $navigationGroup = 'Manajemen';
	protected static ?string $navigationLabel = 'Barang';

	public static function canCreate(): bool
	{
		return in_array(Auth::user()?->role, ['admin', 'gudang']);
	}
	
	public static function canEdit($record): bool
	{
		return in_array(Auth::user()?->role, ['admin', 'gudang']);
	}

	public static function canDelete($record): bool
	{
		return in_array(Auth::user()?->role, ['admin', 'gudang']);
	}
	
	public static function form(Form $form): Form
	{
		return $form
			->schema([
				TextInput::make('nama_barang')
					->label('Nama Barang')
					->placeholder('Masukkan nama barang')
					->required()
					->maxLength(255),
				TextInput::make('stok')
					->label('Stok')
					->placeholder('Masukkan jumlah stok barang')
					->numeric()
					->required()
					->default(0)
					->hiddenOn('edit'),
				Select::make('kategori')
					->label('Kategori')
					->placeholder('-- Pilih Kategori Barang --')
					->required()
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
					]),
				Select::make('satuan')
					->label('Satuan')
					->placeholder('-- Pilih Satuan Barang --')
					->searchable()
					->required()
					->options([
						'pcs' => 'Pcs',   // Untuk barang eceran, kecil
						'unit' => 'Unit', // Untuk barang besar/formal
						'box' => 'Box',   // Untuk kemasan kotak/paket
						'set' => 'Set',   // Untuk barang yang dijual dalam set
					]),
				TextInput::make('harga_beli')
					->label('Harga Beli')
					->placeholder('Masukkan harga beli barang')
					->numeric()
					->required()
					->prefix('Rp')
					->default(0),
				TextInput::make('harga_jual')
					->label('Harga Jual')
					->placeholder('Masukkan harga jual barang')
					->numeric()
					->required()
					->prefix('Rp')
					->default(0),
			]);
	}

	public static function table(Table $table): Table
	{
		return $table
			->columns([
				TextColumn::make('nama_barang')
					->label('Nama Barang')
					->searchable()
					->sortable(),
				TextColumn::make('kategori')
					->label('Kategori')
					->searchable()
					->sortable(),
				TextColumn::make('stok')
					->label('Stok')
					->sortable(),
				TextColumn::make('satuan')
					->label('Satuan'),
				TextColumn::make('harga_beli')
					->label('Harga Beli')
					->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
				TextColumn::make('harga_jual')
					->label('Harga Jual')
					->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
				TextColumn::make('created_at')
					->label('Dibuat Pada')
					->formatStateUsing(fn($state) => $state->format('d-m-Y')),
				TextColumn::make('updated_at')
					->label('Diperbarui Pada')
					->since(),
			])
			->emptyStateHeading('Tidak ada barang yang ditemukan')
			->emptyStateDescription('Silakan tambahkan barang baru untuk memulai.')
			->emptyStateIcon('heroicon-o-cube')
			->filters([
				//
			])
			->actions([
				EditAction::make()
					->color('warning')
					->iconButton()
					->modalHeading('Edit Barang')
					->modalSubmitActionLabel('Simpan')
					->modalIcon('heroicon-o-pencil-square')
					->successNotification(
						Notification::make()
							->success()
							->title('Berhasil Edit')
							->body('Barang telah berhasil diperbarui.')
					),
				DeleteAction::make()
					->color('danger')
					->iconButton()
					->modalHeading('Hapus Barang')
					->modalDescription('Apakah Anda yakin ingin menghapus barang ini?')
					->modalSubmitActionLabel('Hapus')
					->modalIcon('heroicon-o-trash')
					->successNotification(
						Notification::make()
							->success()
							->title('Berhasil Hapus')
							->body('Barang telah berhasil dihapus.')
					),
			])
			->bulkActions([
				DeleteBulkAction::make()
					->modalHeading('Hapus Barang yang dipilih')
					->modalDescription('Apakah Anda yakin ingin menghapus barang yang dipilih?')
					->modalSubmitActionLabel('Hapus'),
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
			'index' => Pages\ListProducts::route('/'),
		];
	}
}
