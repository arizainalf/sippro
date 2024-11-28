<?php

namespace App\Filament\Resources\StokResource\Pages;

use App\Filament\Resources\StokResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateStok extends CreateRecord
{
    protected static string $resource = StokResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            // Ambil data pertama sebagai record utama
            $firstStok = collect($data['stoks'])->first();

            // Buat record utama
            $record = static::getModel()::create($firstStok);

            // Buat record tambahan jika ada
            collect($data['stoks'])->skip(1)->each(function ($stokData) {
                static::getModel()::create($stokData);
            });

            return $record;
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
