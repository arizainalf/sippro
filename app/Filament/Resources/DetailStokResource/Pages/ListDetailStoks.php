<?php

namespace App\Filament\Resources\DetailStokResource\Pages;

use App\Filament\Resources\DetailStokResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailStoks extends ListRecords
{
    protected static string $resource = DetailStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
