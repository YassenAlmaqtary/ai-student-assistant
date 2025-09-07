<?php

namespace App\Filament\Resources\QaSessions\Pages;

use App\Filament\Resources\QaSessions\QaSessionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQaSession extends ViewRecord
{
    protected static string $resource = QaSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
