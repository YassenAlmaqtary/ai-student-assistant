<?php

namespace App\Filament\Resources\Transcripts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TranscriptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('lesson_id')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
