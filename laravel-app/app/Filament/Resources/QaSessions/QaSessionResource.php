<?php

namespace App\Filament\Resources\QaSessions;

use App\Filament\Resources\QaSessions\Pages\CreateQaSession;
use App\Filament\Resources\QaSessions\Pages\EditQaSession;
use App\Filament\Resources\QaSessions\Pages\ListQaSessions;
use App\Filament\Resources\QaSessions\Pages\ViewQaSession;
use App\Filament\Resources\QaSessions\Schemas\QaSessionForm;
use App\Filament\Resources\QaSessions\Schemas\QaSessionInfolist;
use App\Filament\Resources\QaSessions\Tables\QaSessionsTable;
use App\Models\QaSession;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QaSessionResource extends Resource
{
    protected static ?string $model = QaSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'التفاعل الذكي';
    protected static ?string $pluralLabel = 'جلسات السؤال والجواب';
    protected static ?string $label = 'جلسة سؤال وجواب';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return QaSessionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QaSessionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QaSessionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQaSessions::route('/'),
            'create' => CreateQaSession::route('/create'),
            'view' => ViewQaSession::route('/{record}'),
            'edit' => EditQaSession::route('/{record}/edit'),
        ];
    }
}
