<?php

namespace App\Filament\Resources\Chunks\Pages;

use App\Filament\Resources\Chunks\ChunkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChunks extends ListRecords
{
    protected static string $resource = ChunkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
