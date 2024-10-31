<?php

namespace App\Filament\Resources\TypeZipCodeResource\Pages;

use App\Filament\Resources\TypeZipCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTypeZipCode extends CreateRecord
{
    protected static string $resource = TypeZipCodeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
