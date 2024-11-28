<?php

namespace Database\Seeders;

use App\Models\Stok;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        $kategoris = [
            [
                'nama' => 'Tabungan'],
            [
                'nama' => 'ATM'],
        ];

        foreach ($kategoris as $k) {
            \App\Models\Kategori::create([
                'uuid' => Uuid::uuid4()->toString(),
                'nama' => $k['nama'],]);
        }

        $barangs = [
            ['kategori_id' => '1', 'nama' => 'BJB Tandamata'],
            ['kategori_id' => '1', 'nama' => 'TANDAMATA'],
            ['kategori_id' => '1', 'nama' => 'TABUNGANKU'],
            ['kategori_id' => '1', 'nama' => 'TANDAMATA MYFIRST'],
            ['kategori_id' => '1', 'nama' => 'SIMPEL'],
            ['kategori_id' => '1', 'nama' => 'TANDAMATA PURNABAKTI'],
            ['kategori_id' => '1', 'nama' => 'TANDA MATA SIMUDA'],
            ['kategori_id' => '1', 'nama' => 'TANDA MATA BERJANGKA'],
            ['kategori_id' => '1', 'nama' => 'TANDA MATA BISNIS'],
            ['kategori_id' => '1', 'nama' => 'TANDA MATA GOLD'],
            ['kategori_id' => '1', 'nama' => 'SIMPEDA'],
            ['kategori_id' => '1', 'nama' => 'TANDAMATA HAJI'],
            ['kategori_id' => '1', 'nama' => 'DPLK'],
            ['kategori_id' => '2', 'nama' => 'ATM SILVER'],
            ['kategori_id' => '2', 'nama' => 'ATM CLASSIC'],
            ['kategori_id' => '2', 'nama' => 'ATM GOLD'],
            ['kategori_id' => '2', 'nama' => 'ATM SIMPEL'],
            ['kategori_id' => '2', 'nama' => 'ATM TABUNGANKU'],
            ['kategori_id' => '2', 'nama' => 'ATM MYFIRST'],
            ['kategori_id' => '2', 'nama' => 'ATM BJB BISA'],
        ];

        foreach ($barangs as $b) {
            \App\Models\Barang::create([
                'uuid' => Uuid::uuid4()->toString(),
                'kategori_id' => $b['kategori_id'],
                'nama' => $b['nama'],]);
        }

        $stoks = [
            ['barang_id' => '1', 'jumlah' => '100', 'tipe' => 'masuk', 'tanggal' => '2022-01-01'],
            ['barang_id' => '1', 'jumlah' => '100', 'tipe' => 'keluar', 'tanggal' => '2022-01-22'],
            ['barang_id' => '1', 'jumlah' => '100', 'tipe' => 'masuk', 'tanggal' => '2022-02-01'],
            ['barang_id' => '1', 'jumlah' => '100', 'tipe' => 'keluar', 'tanggal' => '2022-02-22'],
            ['barang_id' => '1', 'jumlah' => '100', 'tipe' => 'masuk', 'tanggal' => '2022-03-01'],
            ['barang_id' => '1', 'jumlah' => '100', 'tipe' => 'keluar', 'tanggal' => '2022-03-22'],
            ['barang_id' => '2', 'jumlah' => '150', 'tipe' => 'masuk', 'tanggal' => '2022-01-01'],
            ['barang_id' => '2', 'jumlah' => '100', 'tipe' => 'keluar', 'tanggal' => '2022-01-22'],
            ['barang_id' => '2', 'jumlah' => '200', 'tipe' => 'masuk', 'tanggal' => '2022-02-01'],
            ['barang_id' => '2', 'jumlah' => '125', 'tipe' => 'keluar', 'tanggal' => '2022-02-22'],
            ['barang_id' => '2', 'jumlah' => '175', 'tipe' => 'masuk', 'tanggal' => '2022-03-01'],
            ['barang_id' => '2', 'jumlah' => '155', 'tipe' => 'keluar', 'tanggal' => '2022-03-22'],
        ];

        foreach ($stoks as $s) {
            Stok::create([
                'uuid' => Uuid::uuid4()->toString(),
                'barang_id' => $s['barang_id'],
                'tipe' => $s['tipe'],
                'stok' => $s['jumlah'],
                'tanggal' => $s['tanggal'],]);
            if ($s['tipe'] === 'masuk') {
                $barang = Barang::find($s['barang_id']);
                $barang->stok += $s['jumlah'];
                $barang->save();
            } else {
                $barang = Barang::find($s['barang_id']);
                $barang->stok -= $s['jumlah'];
                $barang->save();
                dd($barang->stok);
            }
        }
    }
}
