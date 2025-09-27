<?php

namespace App\Filament\Asesor\Resources\OrderResource\Pages;

use App\Filament\Asesor\Resources\OrderResource;
use App\Models\Zipcode;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // TODO:: ¿Es necesario calcular si requiere factura o no?
        $data['user_id'] = Auth::user()->id;

        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $zipcode = Zipcode::where('zipcode', $data['zipcode'])->first();
        if ($zipcode) {
            $data['country'] = $zipcode->country;
            $data['state'] = $zipcode->state;
            $data['municipality'] = $zipcode->municipality;
            $data['city'] = $zipcode->city;
        }
        return $data;
    }
}
