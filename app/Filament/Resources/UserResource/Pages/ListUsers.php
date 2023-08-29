<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Added filter tabs on Workorder resource
     */
    public function getTabs(): array
    {
        return [
            null => ListRecords\Tab::make('All')->icon('heroicon-o-bars-4'),
            'Admin' => ListRecords\Tab::make()->query(fn ($query) => $query->role('Admin'))->icon('heroicon-o-user-plus'),
            'Vendor' => ListRecords\Tab::make()->query(fn ($query) => $query->role('Vendor'))->icon('heroicon-o-wrench-screwdriver'),
            'Client' => ListRecords\Tab::make()->query(fn ($query) => $query->role('Client'))->icon('heroicon-o-building-office'),
        ];
    }
}
