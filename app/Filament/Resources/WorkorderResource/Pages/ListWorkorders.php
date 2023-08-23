<?php

namespace App\Filament\Resources\WorkorderResource\Pages;

use App\Filament\Resources\WorkorderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorkorders extends ListRecords
{
    protected static string $resource = WorkorderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
