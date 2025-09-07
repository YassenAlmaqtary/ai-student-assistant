<?php

namespace App\Filament\Resources\Summaries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SummaryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lesson_id')
                ->label('الدرس')
                ->relationship('lesson','title')
                ->searchable()->preload()->required(),
            TextInput::make('style')
                ->label('نمط الملخص')
                ->placeholder('مختصر / تفصيلي / نقاط ...')
                ->maxLength(120)
                ->required(),
            Textarea::make('text')
                ->label('النص الملخص')
                ->placeholder('اكتب أو ألصق الملخص هنا ...')
                ->autosize()
                ->rows(6)
                ->maxLength(10000)
                ->required()
                ->columnSpanFull(),
        ]);
    }
}
