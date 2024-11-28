<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Stok;
use App\Models\Barang;
use Filament\Pages\Page;
use App\Models\StokMasuk;
use App\Models\StokKeluar;

class Report extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.report';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?string $slug = 'laporan';

    protected static ?int $navigationSort = 5;

    public $reportType;

    public $month;

    public $year;

    public $data = [];

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(3)->schema([
                Forms\Components\Select::make('reportType')
                    ->label('Tipe Laporan')
                    ->options([
                        'monthly' => 'Bulanan',
                        'yearly' => 'Tahunan',
                    ])
                    ->reactive()
                    ->required(),

                Forms\Components\Select::make('month')
                    ->label('Bulan')
                    ->options([
                        '1' => 'Januari',
                        '2' => 'Februari',
                        '3' => 'Maret',
                        '4' => 'April',
                        '5' => 'Mei',
                        '6' => 'Juni',
                        '7' => 'Juli',
                        '8' => 'Agustus',
                        '9' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->hidden(fn($get) => $get('reportType') !== 'monthly')
                    ->required(fn($get) => $get('reportType') === 'monthly'),

                Forms\Components\TextInput::make('year')
                    ->label('Tahun')
                    ->numeric()
                    ->required()
                    ->reactive(),
            ]),
        ];
    }

    public function submit()
    {
        $this->data['barang'] = Barang::all();

        $queryStokBefore = Stok::with('barang')
            ->whereYear('tanggal', '<', $this->year);
        $queryStok = Stok::with('barang')
            ->whereYear('tanggal', $this->year);
        $queryStokAfter = Stok::with('barang')
            ->whereYear('tanggal', '>', $this->year);

        if ($this->reportType === 'monthly') {
            $queryStokBefore->whereMonth('tanggal', '<', $this->month);
            $queryStok->whereMonth('tanggal', $this->month);
            $queryStokAfter->whereMonth('tanggal', '>', $this->month);
        }

        $this->data['stokBefore'] = $queryStokBefore->get();
        $this->data['stok'] = $queryStok->get();
        $this->data['stokAfter'] = $queryStokAfter->get();

        $this->dispatch('refreshTable');
    }
}
