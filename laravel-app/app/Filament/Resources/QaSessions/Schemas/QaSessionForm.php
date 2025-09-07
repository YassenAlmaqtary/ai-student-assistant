<?php

namespace App\Filament\Resources\QaSessions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class QaSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lesson_id')
                ->label('الدرس')
                ->relationship('lesson','title')
                ->searchable()->preload()->required(),
            TextInput::make('question')
                ->label('السؤال')
                ->placeholder('أدخل السؤال')
                ->required()->maxLength(500),
            Textarea::make('answer')
                ->label('الإجابة')
                ->placeholder('الإجابة المعالجة ...')
                ->autosize()->rows(6)
                ->maxLength(15000)
                ->required()
                ->columnSpanFull(),
            Textarea::make('sources')
                ->label('مصادر (JSON)')
                ->placeholder('["url1","url2"]')
                ->rows(3)
                ->maxLength(5000)
                ->default(null)
                ->columnSpanFull(),
        ]);
    }
}
