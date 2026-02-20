<?php

namespace App\Filament\Resources\MaintenanceReminderResource\Pages;

use App\Filament\Resources\MaintenanceReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMaintenanceReminders extends ListRecords
{
    protected static string $resource = MaintenanceReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
