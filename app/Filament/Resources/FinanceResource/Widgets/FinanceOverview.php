<?php

namespace App\Filament\Resources\FinanceResource\Widgets;

use App\Models\Finance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Enums\IconPosition;

class FinanceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalIn = Finance::where('jenis', 'pemasukan')->sum('nominal');
        $totalOut = Finance::where('jenis', 'pengeluaran')->sum('nominal');

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalIn, 0, ',', '.'))
                ->description('Jumlah Uang Masuk')
                ->descriptionIcon('heroicon-o-arrow-trending-up', IconPosition::Before)
                ->color('success')
                ->chart([1, 5, 30, 50]),

            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalOut, 0, ',', '.'))
                ->description('Jumlah Uang Keluar')
                ->descriptionIcon('heroicon-o-arrow-trending-down', IconPosition::Before)
                ->color('danger')
                ->chart([1, 5, 10, 50]),

            Stat::make('Saldo', 'Rp ' . number_format($totalIn - $totalOut, 0, ',', '.'))
                ->description('Selisih Pemasukan dan Pengeluaran')
                ->descriptionIcon('heroicon-o-banknotes', IconPosition::Before)
                ->color('primary')
                ->chart([1, 20, 30, 50]),
        ];
    }
}
