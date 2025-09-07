<?php

namespace App\Filament\Resources\Chunks\Pages;

use App\Filament\Resources\Chunks\ChunkResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditChunk extends EditRecord
{
    protected static string $resource = ChunkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
