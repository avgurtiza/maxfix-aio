<?php

namespace App\Filament\Resources\ServiceShopResource\Pages;

use App\Filament\Resources\ServiceShopResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceShops extends ListRecords
{
    protected static string $resource = ServiceShopResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
