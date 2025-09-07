<?php

namespace App\Filament\Resources\Chunks\Pages;

use App\Filament\Resources\Chunks\ChunkResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChunk extends ViewRecord
{
    protected static string $resource = ChunkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
