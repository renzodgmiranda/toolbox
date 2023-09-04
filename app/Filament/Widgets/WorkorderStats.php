<?php

namespace App\Filament\Widgets;

use App\Models\Workorder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class WorkorderStats extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected function getStats(): array
    {
        $user = Auth::user(); // Get the currently authenticated user
        $vendorId = $user->id;

        // If the user is a vendor
        if ($user->hasRole('Vendor')) {
            return [
                Stat::make('Pending WOs', Workorder::where('wo_status', 'Pending')->where('user_id', $vendorId)->count())
                    ->icon('heroicon-o-bolt'),

                Stat::make('Ongoing WOs', Workorder::where('wo_status', 'Ongoing')->where('user_id', $vendorId)->count())
                    ->icon('heroicon-o-arrow-path'),

                Stat::make('Completed WOs', Workorder::where('wo_status', 'Completed')->where('user_id', $vendorId)->count())
                    ->icon('heroicon-o-check'),
            ];
        }

        // For admin and client roles, or any other roles
        return [
            Stat::make('Pending WOs', Workorder::where('wo_status', 'Pending')->count())
                ->icon('heroicon-o-bolt'),

            Stat::make('Ongoing WOs', Workorder::where('wo_status', 'Ongoing')->count())
                ->icon('heroicon-o-arrow-path'),

            Stat::make('Completed WOs', Workorder::where('wo_status', 'Completed')->count())
                ->icon('heroicon-o-check'),
        ];
    }
}
