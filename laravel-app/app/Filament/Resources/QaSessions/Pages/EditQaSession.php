<?php

namespace App\Filament\Resources\QaSessions\Pages;

use App\Filament\Resources\QaSessions\QaSessionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditQaSession extends EditRecord
{
    protected static string $resource = QaSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
