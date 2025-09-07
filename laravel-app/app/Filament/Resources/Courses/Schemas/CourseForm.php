<?php

namespace App\Filament\Resources\Courses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('المالك / المُحاضر')
                ->relationship('user','name')
                ->searchable()->preload()->required(),
            TextInput::make('title')
                ->label('عنوان المقرر')
                ->placeholder('أدخل عنواناً واضحاً')
                ->required()->maxLength(255),
        ]);
    }
}
