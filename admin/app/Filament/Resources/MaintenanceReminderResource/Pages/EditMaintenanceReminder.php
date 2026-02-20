<?php

namespace App\Filament\Resources\MaintenanceReminderResource\Pages;

use App\Filament\Resources\MaintenanceReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaintenanceReminder extends EditRecord
{
    protected static string $resource = MaintenanceReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
