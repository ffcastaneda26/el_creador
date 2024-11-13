<?php

namespace App\Filament\Asesor\Resources\CotizationResource\Pages;

use App\Filament\Asesor\Resources\CotizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCotizations extends ListRecords
{
    protected static string $resource = CotizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
