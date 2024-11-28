<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Resources\Pages\Page;

class Report extends Page
{
    protected static string $resource = BarangResource::class;

    protected static string $view = 'filament.resources.barang-resource.pages.report';

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
