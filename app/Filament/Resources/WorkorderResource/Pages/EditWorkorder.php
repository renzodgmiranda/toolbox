<?php

namespace App\Filament\Resources\WorkorderResource\Pages;

use App\Filament\Resources\WorkorderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkorder extends EditRecord
{
    protected static string $resource = WorkorderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
