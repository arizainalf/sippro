<?php

namespace App\Filament\Resources\BarangResource\Widgets;

use App\Models\Stok;
use App\Models\Barang;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StokBarangOverview extends BaseWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $listBarang = Barang::all();
        $barang = Barang::count();
        $stokKeluar = Stok::where('tipe', 'keluar')->sum('stok');
        $stokMasuk = Stok::where('tipe', 'masuk')->sum('stok');
        $stokAkhir = Stok::all()->map(function ($stok) {
            $stok->stok = (int) $stok->stok;
            return $stok;
        })->sum('stok');

        $stats = [];

        $stats[] = Stat::make('Stok Masuk', $stokMasuk)
            ->icon('heroicon-m-user')
            ->chart([1, 6, 10, 5, 14])
            ->color('primary');

        $stats[] = Stat::make('Stok Keluar', $stokKeluar)
            ->icon('heroicon-m-user')
            ->chart([1, 6, 10, 5, 14])
            ->color('primary');

        $stats[] = Stat::make('Stok Akhir', $stokAkhir)
            ->icon('heroicon-m-user')
            ->chart([1, 6, 10, 5, 14])
            ->color('primary');

        // Widget untuk setiap barang
        foreach ($listBarang as $b) {
            $stats[] = Stat::make($b->nama, $b->stok)
                ->description('Stok Akhir')
                ->icon('heroicon-m-user')
                ->chart(array_map(fn() => rand(1, 20), range(1, 5)))
                ->color('success');
        }

        return $stats;
    }
}
