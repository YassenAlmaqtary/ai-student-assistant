<?php

namespace App\Filament\Resources\Transcripts;

use App\Filament\Resources\Transcripts\Pages\CreateTranscript;
use App\Filament\Resources\Transcripts\Pages\EditTranscript;
use App\Filament\Resources\Transcripts\Pages\ListTranscripts;
use App\Filament\Resources\Transcripts\Pages\ViewTranscript;
use App\Filament\Resources\Transcripts\Schemas\TranscriptForm;
use App\Filament\Resources\Transcripts\Schemas\TranscriptInfolist;
use App\Filament\Resources\Transcripts\Tables\TranscriptsTable;
use App\Models\Transcript;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TranscriptResource extends Resource
{
    protected static ?string $model = Transcript::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'المعالجة الدلالية';
    protected static ?string $pluralLabel = 'النصوص المفرغة';
    protected static ?string $label = 'نص مفرغ';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return TranscriptForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TranscriptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TranscriptsTable::configure($table);
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
            'index' => ListTranscripts::route('/'),
            'create' => CreateTranscript::route('/create'),
            'view' => ViewTranscript::route('/{record}'),
            'edit' => EditTranscript::route('/{record}/edit'),
        ];
    }
}
