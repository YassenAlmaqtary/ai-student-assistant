<?php

namespace App\Filament\Resources\Chunks;

use App\Filament\Resources\Chunks\Pages\CreateChunk;
use App\Filament\Resources\Chunks\Pages\EditChunk;
use App\Filament\Resources\Chunks\Pages\ListChunks;
use App\Filament\Resources\Chunks\Pages\ViewChunk;
use App\Filament\Resources\Chunks\Schemas\ChunkForm;
use App\Filament\Resources\Chunks\Schemas\ChunkInfolist;
use App\Filament\Resources\Chunks\Tables\ChunksTable;
use App\Models\Chunk;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChunkResource extends Resource
{
    protected static ?string $model = Chunk::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static UnitEnum|string|null $navigationGroup = 'المعالجة الدلالية';
    protected static ?string $pluralLabel = 'المقاطع';
    protected static ?string $label = 'مقطع';
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return ChunkForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ChunkInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChunksTable::configure($table);
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
            'index' => ListChunks::route('/'),
            'create' => CreateChunk::route('/create'),
            'view' => ViewChunk::route('/{record}'),
            'edit' => EditChunk::route('/{record}/edit'),
        ];
    }
}
