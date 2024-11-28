<x-filament::page>
    <x-filament::card class="h-full">
        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}
            <x-filament::button type="submit"
                class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded">
                Submit
            </x-filament::button>
        </form>

        @php
            $namaBulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];
        @endphp

        @if (!empty($data))
            @if ($reportType === 'monthly')
                <h2 class="text-lg font-semibold mt-6">Laporan {{ $month ? $namaBulan[(int) $month] : '' }}
                    {{ $year }}</h2>
                <br>

                <div class="overflow-auto h-full">
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg w-full h-full">
                        <thead class="bg-gray-100 sticky top-0 z-10">
                            <tr class="text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Kategori</th>
                                <th class="px-6 py-3">Product</th>
                                <th class="px-6 py-3">Stok Awal</th>
                                <th class="px-6 py-3">Stok Masuk</th>
                                <th class="px-6 py-3">Stok Keluar</th>
                                <th class="px-6 py-3">Stok Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php

                                $stokBefore = $data['stokBefore']->reduce(function ($carry, $before) {
                                    return $carry + ($before->jenis === 'masuk' ? $before->stok : -$before->stok);
                                }, 0);

                                $sumStokAwal = $sumStokMasuk = $sumStokKeluar = $sumStokAkhir = 0;
                            @endphp
                            @foreach ($data['barang'] as $item)
                                @php
                                    $stokMasuk = $data['stok']
                                        ->where('barang_id', $item->id)
                                        ->where('tipe', 'masuk')
                                        ->sum('stok');
                                    $stokKeluar = $data['stok']
                                        ->where('barang_id', $item->id)
                                        ->where('tipe', 'keluar')
                                        ->sum('stok');
                                    $stokAkhir = $item->stok + $stokMasuk - $stokKeluar;

                                    $sumStokAwal += $item->stok;
                                    $sumStokMasuk += $stokMasuk;
                                    $sumStokKeluar += $stokKeluar;
                                    $sumStokAkhir += $stokAkhir;
                                @endphp
                                <tr class="hover:bg-gray-100 transition duration-200 ease-in-out">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->kategori->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokBefore }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokMasuk }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokKeluar }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokAkhir }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-100 font-semibold">
                                <td class="px-6 py-4 text-gray-900" colspan="3">Total</td>
                                <td class="px-6 py-4 text-gray-900">{{ $sumStokAwal }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $sumStokMasuk }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $sumStokKeluar }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $sumStokAkhir }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <br>
            @elseif ($reportType === 'yearly')
                <h2 class="text-lg font-semibold mt-6">Laporan Tahunan {{ $year }}</h2>
                <br>

                @php
                    $bulanDenganStok = $data['stok']
                        ->map(function ($stok) {
                            return \Carbon\Carbon::parse($stok->tanggal)->format('m');
                        })
                        ->unique()
                        ->sortDesc();
                    $stokAkhirSebelumnya = [];
                @endphp
                @if(isset($data['barang']) && count($data['barang']) > 0)
                @foreach ($bulanDenganStok as $bulan)
                    @php
                        $sumStokAwal = $sumStokMasuk = $sumStokKeluar = $sumStokAkhir = 0;
                    @endphp

                    <h3 class="text-md font-semibold mt-4">{{ $namaBulan[(int)$bulan] ? $namaBulan[(int)$bulan] : 'Bulan Tidak Diketahui' }} {{ $year }}</h3>
                    <br>

                    <div class="overflow-auto h-full">
                        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg w-full h-full">
                            <thead class="bg-gray-100 sticky top-0 z-10">
                                <tr class="text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    <th class="px-6 py-3">No</th>
                                    <th class="px-6 py-3">Kategori</th>
                                    <th class="px-6 py-3">Product</th>
                                    <th class="px-6 py-3">Stok Awal</th>
                                    <th class="px-6 py-3">Stok Masuk</th>
                                    <th class="px-6 py-3">Stok Keluar</th>
                                    <th class="px-6 py-3">Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($data['barang'] as $index => $item)
                                    @php
                                        // Set default values for arrays
                                        $stokAfterMasuk[$item->id] = $data['stokAfter']
                                            ->where('tipe', 'masuk')
                                            ->where('barang_id', $item->id)
                                            ->sum('stok') ?? 0;

                                        $stokAfterKeluar[$item->id] = $data['stokAfter']
                                            ->where('tipe', 'keluar')
                                            ->where('barang_id', $item->id)
                                            ->sum('stok') ?? 0;

                                        $stokAkhir[$item->id] = $stokAkhir[$item->id] ?? 0;
                                        $stokAkhirSebelumnya[$item->id] = $stokAkhirSebelumnya[$item->id] ?? 0;

                                        if ($stokAkhirSebelumnya[$item->id] === 0) {
                                            $stokAkhir[$item->id] =
                                                $item->stok + $stokAfterKeluar[$item->id] - $stokAfterMasuk[$item->id];
                                        } else {
                                            $stokAkhir[$item->id] =
                                                $stokAkhirSebelumnya[$item->id];
                                        }

                                        $stokMasuk[$item->id] = $data['stok']
                                            ->where('barang_id', $item->id)
                                            ->where('tipe', 'masuk')
                                            ->filter(function ($stok) use ($bulan) {
                                                return \Carbon\Carbon::parse($stok->tanggal)->month == $bulan;
                                            })
                                            ->sum('stok') ?? 0;

                                        $stokKeluar[$item->id] = $data['stok']
                                            ->where('barang_id', $item->id)
                                            ->where('tipe', 'keluar')
                                            ->filter(function ($stok) use ($bulan) {
                                                return \Carbon\Carbon::parse($stok->tanggal)->month == $bulan;
                                            })
                                            ->sum('stok') ?? 0;

                                        $stokAwal[$item->id] = $stokAkhir[$item->id] - $stokMasuk[$item->id] + $stokKeluar[$item->id];
                                        $stokAkhirSebelumnya[$item->id] = $stokAwal[$item->id];

                                        // Update sum untuk total stok bulanan
                                        $sumStokAwal += $stokAwal[$item->id];
                                        $sumStokMasuk += $stokMasuk[$item->id];
                                        $sumStokKeluar += $stokKeluar[$item->id];
                                        $sumStokAkhir += $stokAkhir[$item->id];
                                    @endphp
                                    <tr class="hover:bg-gray-100 transition duration-200 ease-in-out">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->kategori->nama ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokAwal[$item->id] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokMasuk[$item->id] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokKeluar[$item->id] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stokAkhir[$item->id] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 font-semibold">
                                    <td class="px-6 py-4 text-gray-900" colspan="3">Total</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $sumStokAwal }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $sumStokMasuk }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $sumStokKeluar }}</td>
                                    <td class="px-6 py-4 text-gray-900">{{ $sumStokAkhir }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        Data Produk Tidak Ditemukan.
                    </td>
                </tr>
            @endif


            @endif
        @endif
    </x-filament::card>
</x-filament::page>
