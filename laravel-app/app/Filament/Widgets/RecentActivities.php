<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Spatie\Activitylog\Models\Activity;

class RecentActivities extends Widget
{
    protected string $view = 'filament.widgets.recent-activities';

    protected int|string|array $columnSpan = 'full';

    public function getData(): array
    {
        return [
            'activities' => Activity::latest()->limit(10)->get(),
        ];
    }
}
