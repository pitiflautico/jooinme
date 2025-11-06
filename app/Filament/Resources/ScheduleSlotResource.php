<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleSlotResource\Pages;
use App\Filament\Resources\ScheduleSlotResource\RelationManagers;
use App\Models\ScheduleSlot;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleSlotResource extends Resource
{
    protected static ?string $model = ScheduleSlot::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListScheduleSlots::route('/'),
            'create' => Pages\CreateScheduleSlot::route('/create'),
            'edit' => Pages\EditScheduleSlot::route('/{record}/edit'),
        ];
    }
}
