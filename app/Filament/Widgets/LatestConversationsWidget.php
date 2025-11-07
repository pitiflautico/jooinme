<?php

namespace App\Filament\Widgets;

use App\Models\Conversation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestConversationsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Conversation::query()
                    ->with(['owner', 'topic'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Organizador')
                    ->searchable(),
                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Tema')
                    ->badge(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'online' => '🌐 Online',
                        'in_person' => '📍 Presencial',
                        'hybrid' => '🔄 Híbrido',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('current_participants')
                    ->label('Participantes')
                    ->formatStateUsing(fn ($state, $record): string =>
                        $state . '/' . $record->max_participants
                    ),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->heading('Últimas Conversaciones Creadas');
    }
}
