<?php

namespace App\Filament\Resources\Chunks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ChunkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lesson_id')
                ->label('الدرس')
                ->relationship('lesson','title')
                ->searchable()->preload()->required(),
            TextInput::make('order')
                ->label('الترتيب')
                ->numeric()->default(0)
                ->hint('يحدد موضع المقطع داخل الدرس'),
            Textarea::make('text')
                ->label('النص')
                ->placeholder('أدخل نص المقطع ...')
                ->autosize()
                ->required()
                ->rows(6)
                ->maxLength(8000)
                ->columnSpanFull(),
            Textarea::make('embedding')
                ->label('تمثيل المتجه (Embedding)')
                ->placeholder('JSON أو قائمة أرقام ... (اختياري)')
                ->rows(4)
                ->maxLength(20000)
                ->columnSpanFull(),
        ]);
    }
}
