<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStats extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::make()->count())
                ->icon('heroicon-o-user-group'),
            Stat::make('Admin', User::role('Admin')->count())
                ->icon('heroicon-o-user-plus')
                ->description('Role'),
            Stat::make('Vendor', User::role('Vendor')->count())
                ->icon('heroicon-o-wrench-screwdriver')
                ->description('Role'),
            Stat::make('Client', User::role('Client')->count())
                ->icon('heroicon-o-building-office')
                ->description('Role'),
        ];
    }
}
