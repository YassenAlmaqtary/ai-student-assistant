<?php

namespace App\Filament\Resources\Documents\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('lesson_id')
                ->label('الدرس')
                ->relationship('lesson','title')
                ->searchable()->preload()->required(),
            Select::make('type')
                ->label('النوع')
                ->options([
                    'transcript' => 'نص مفرغ',
                    'attachment' => 'مرفق',
                    'resource' => 'مرجع',
                ])->required(),
            FileUpload::make('s3_path')
                ->label('ملف المستند')
                ->disk('public')
                ->directory(fn($get) => $get('lesson_id') ? 'documents/lesson-'.$get('lesson_id') : 'documents/tmp')
                ->preserveFilenames()
                ->visibility('public')
                ->acceptedFileTypes(['application/pdf','text/plain','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document','image/*'])
                ->helperText('يحفظ في القرص public ويمكن الوصول له عبر /storage/...')
                ->required(),
        ]);
    }
}
