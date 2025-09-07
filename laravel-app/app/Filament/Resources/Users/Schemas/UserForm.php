<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('الاسم')
                ->required(),
            TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->email()->required(),
            DateTimePicker::make('email_verified_at')
                ->label('تاريخ التحقق من البريد'),
            Select::make('role')
                ->label('الدور')
                ->options(['admin' => 'مدير', 'student' => 'طالب'])
                ->default('student')
                ->required(),
            TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->revealable()
                ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                ->required(fn($record) => $record === null)
                ->hint('اترك الحقل فارغاً إن لم ترغب بتغييره')
                ->maxLength(64),
        ]);
    }
}
