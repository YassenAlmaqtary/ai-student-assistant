<?php

namespace App\Filament\Resources\Lessons\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class LessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('المضاف بواسطة')
                ->relationship('user','name')
                ->searchable()->preload()->required(),
            Select::make('course_id')
                ->label('المقرر')
                ->relationship('course','title')
                ->searchable()->preload(),
            TextInput::make('title')
                ->label('عنوان الدرس')
                ->placeholder('أدخل عنوان الدرس')
                ->required()->maxLength(255),
            Select::make('type')
                ->label('نوع المحتوى')
                ->options([
                    'video' => 'فيديو',
                    'audio' => 'صوتي',
                    'pdf' => 'ملف PDF',
                    'image' => 'صورة',
                    'doc' => 'مستند'
                ])->required(),
            FileUpload::make('s3_path')
                ->label('الملف / المحتوى')
                ->disk('public')
                ->directory(fn($get) => $get('course_id') ? 'lessons/'.$get('course_id') : 'lessons/tmp')
                ->preserveFilenames()
                ->visibility('public')
                ->acceptedFileTypes(['video/*','audio/*','application/pdf','image/*','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->helperText('سيتم حفظ الملف داخل storage/app/public ... استخدم "php artisan storage:link" إن لم يكن الرابط مفعلاً')
                ->required(),
            Select::make('status')
                ->label('الحالة')
                ->options([
                    'pending' => 'قيد الانتظار',
                    'processing' => 'جاري المعالجة',
                    'ready' => 'جاهز',
                    'failed' => 'فشل'
                ])->default('pending')->required(),
        ]);
    }
}
