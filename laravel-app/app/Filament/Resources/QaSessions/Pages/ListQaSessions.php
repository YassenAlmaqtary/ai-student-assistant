<?php

namespace App\Filament\Resources\QaSessions\Pages;

use App\Filament\Resources\QaSessions\QaSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQaSessions extends ListRecords
{
    protected static string $resource = QaSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
