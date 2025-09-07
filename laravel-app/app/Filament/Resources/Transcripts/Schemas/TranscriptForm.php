<?php

namespace App\Filament\Resources\Transcripts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class TranscriptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lesson_id')
                ->label('الدرس')
                ->relationship('lesson','title')
                ->searchable()->preload()->required(),
            Textarea::make('text')
                ->label('النص المفرغ')
                ->placeholder('ألصق النص المفرغ ...')
                ->autosize()->rows(8)
                ->maxLength(20000)
                ->required()
                ->columnSpanFull(),
            Textarea::make('timestamps')
                ->label('الطوابع الزمنية (JSON)')
                ->placeholder('[{"start":0,"end":3.2,"text":"..."}]')
                ->rows(4)
                ->maxLength(20000)
                ->default(null)
                ->columnSpanFull(),
        ]);
    }
}
