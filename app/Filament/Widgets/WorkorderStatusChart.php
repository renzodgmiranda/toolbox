<?php

namespace App\Filament\Widgets;

use App\Models\Workorder;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class WorkorderStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '200px';

    protected static ?string $heading = 'Monthly WO Status Distribution';

    protected function getData(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
    
        $completedCount = Workorder::where('wo_status', 'Completed')->whereBetween('created_at', [$startDate, $endDate])->count();
        $ongoingCount = WorkOrder::where('wo_status', 'Ongoing')->whereBetween('created_at', [$startDate, $endDate])->count();
        $pendingCount = WorkOrder::where('wo_status', 'Pending')->whereBetween('created_at', [$startDate, $endDate])->count();
    
        return [
            'labels' => ['Completed', 'Ongoing', 'Pending'],
            'datasets' => [
                [
                    'data' => [$completedCount, $ongoingCount, $pendingCount],
                    'backgroundColor' => ['#4CAF50', '#FF9800', '#E91E63'], // example colors, adjust as needed
                ],
            ],
        ];
    }    

    protected function getType(): string
    {
        return 'pie';
    }
}