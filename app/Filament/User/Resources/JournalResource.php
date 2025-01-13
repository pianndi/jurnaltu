<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\JournalResource\Pages;
use App\Filament\User\Resources\JournalResource\RelationManagers;
use App\Models\Journal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;
class JournalResource extends Resource
{
    protected static ?string $model = Journal::class;
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';


    public static function canCreate(): bool
    {
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->date()->formatStateUsing(
                    fn(string $state) => Carbon::parse($state)->locale('id')->translatedFormat('l, d M Y')
                )->sortable(),
                TextColumn::make('waktu_mulai')->formatStateUsing(
                    fn(string $state, $record) => $record->waktu_mulai ? ($record->waktu_mulai . " - " . $record->waktu_selesai) : "-"
                )->label('Jam')->sortable(),
                TextColumn::make('keterangan')->formatStateUsing(
                    fn(string $state, $record) => !empty($record->pekerjaan) ? $record->pekerjaan : $state
                )
                    ->description(fn(Journal $journal) => !empty($journal->pekerjaan) ? $journal->keterangan : null),
                TextColumn::make('status_id'),

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                        Tables\Actions\DeleteBulkAction::make(),
                    ]),
                ]);
        ;
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
            'index' => Pages\ListJournals::route('/'),
            'create' => Pages\CreateJournal::route('/create'),
            'edit' => Pages\EditJournal::route('/{record}/edit'),
        ];
    }
}
