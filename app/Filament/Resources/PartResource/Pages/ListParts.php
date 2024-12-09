<?php

namespace App\Filament\Resources\PartResource\Pages;

use Filament\Actions;
use App\Filament\Resources\PartResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Components\Tab;



class ListParts extends ListRecords
{
    protected static string $resource = PartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            __('All') => Tab::make(),
            __('Parent Parts') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('parent_part', true)),
            __('Child Parts') => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('parent_part', false)),
        ];
    }

}
