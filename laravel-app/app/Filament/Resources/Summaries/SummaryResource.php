<?php

namespace App\Filament\Resources\Summaries;

use App\Filament\Resources\Summaries\Pages\CreateSummary;
use App\Filament\Resources\Summaries\Pages\EditSummary;
use App\Filament\Resources\Summaries\Pages\ListSummaries;
use App\Filament\Resources\Summaries\Pages\ViewSummary;
use App\Filament\Resources\Summaries\Schemas\SummaryForm;
use App\Filament\Resources\Summaries\Schemas\SummaryInfolist;
use App\Filament\Resources\Summaries\Tables\SummariesTable;
use App\Models\Summary;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SummaryResource extends Resource
{
    protected static ?string $model = Summary::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'المعالجة الدلالية';
    protected static ?string $pluralLabel = 'الملخصات';
    protected static ?string $label = 'ملخص';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return SummaryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SummaryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SummariesTable::configure($table);
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
            'index' => ListSummaries::route('/'),
            'create' => CreateSummary::route('/create'),
            'view' => ViewSummary::route('/{record}'),
            'edit' => EditSummary::route('/{record}/edit'),
        ];
    }
}
