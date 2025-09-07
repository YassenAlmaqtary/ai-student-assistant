<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('المستخدمون', number_format(User::count()))
                ->description('إجمالي المستخدمين')
                ->color('primary'),
            Stat::make('المقررات', number_format(Course::count()))
                ->description('عدد المقررات')
                ->color('success'),
            Stat::make('الدروس', number_format(Lesson::count()))
                ->description('عدد الدروس')
                ->color('warning'),
        ];
    }
}
