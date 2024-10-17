<?php

namespace App\Filament\Resources\BarangResource\Widgets;

use App\Models\Barang;
use App\Models\StokKeluar;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StokBarangOverview extends BaseWidget
{

    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        $listBarang = Barang::all();
        $barang = Barang::count();
        $stokKeluar = StokKeluar::count();

        $stats = [];

        // Loop untuk setiap barang
        foreach ($listBarang as $b) {
            $stats[] = Stat::make($b->nama, $b->stokMasuks()->count())
                ->icon('heroicon-m-user')
                ->chart([1, 6, 10, 5, 14])
                ->color('success');
        }

        // Stat umum
        $stats[] = Stat::make('Barang', $barang)
            ->icon('heroicon-m-building-office')
            ->chart([1, 6, 10, 5, 14])
            ->color('primary');

        $stats[] = Stat::make('Stok Keluar', $stokKeluar)
            ->icon('heroicon-m-user')
            ->chart([1, 6, 10, 5, 14])
            ->color('success');

        return $stats;
    }
}
