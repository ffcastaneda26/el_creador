<?php

namespace App\Filament\Resources\TypeZipCodeResource\Pages;

use App\Filament\Resources\TypeZipCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTypeZipCodes extends ListRecords
{
    protected static string $resource = TypeZipCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
