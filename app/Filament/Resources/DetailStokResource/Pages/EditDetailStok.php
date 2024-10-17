<?php

namespace App\Filament\Resources\DetailStokResource\Pages;

use App\Filament\Resources\DetailStokResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailStok extends EditRecord
{
    protected static string $resource = DetailStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
