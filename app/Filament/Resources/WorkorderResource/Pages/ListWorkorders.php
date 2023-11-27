<?php

namespace App\Filament\Resources\WorkorderResource\Pages;

use App\Filament\Resources\WorkorderResource;
use App\Filament\Widgets\WorkorderStats;
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

    /**
     * Added filter tabs on Workorder resource
     */
    public function getTabs(): array
    {
        return [
            null => ListRecords\Tab::make('All')->icon('heroicon-o-bars-4'),
            'Pending' => ListRecords\Tab::make()->query(fn ($query) => $query->where('wo_status', 'Pending'))->icon('heroicon-o-bolt'),
            'Ongoing' => ListRecords\Tab::make()->query(fn ($query) => $query->where('wo_status', 'Ongoing'))->icon('heroicon-o-arrow-path'),
            'Completed' => ListRecords\Tab::make()->query(fn ($query) => $query->where('wo_status', 'Completed'))->icon('heroicon-o-check'),
        ];
    }

    /**
     * List all available widgets on header for Workorder resource
     */
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\WorkorderStats::class,
        ];
    }

    /**
     * List all available widgets on footer for Workorder resource
     */
    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Resources\WorkorderResource\Widgets\VendorMap::class,
        ];
    }
}
