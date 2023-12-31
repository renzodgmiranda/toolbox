<?php

namespace App\Filament\Resources\WorkorderResource\Pages;

use App\Filament\Resources\WorkorderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkorder extends CreateRecord
{
    protected static string $resource = WorkorderResource::class;

    /**
     * Redirect back to the list page after creation
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
