<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Conversation;
use App\Models\Participation;
use App\Models\Feedback;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $activeUsers = User::where('is_active', true)->count();

        $totalConversations = Conversation::count();
        $activeConversations = Conversation::where('is_active', true)->count();
        $newConversationsThisMonth = Conversation::whereMonth('created_at', now()->month)->count();

        $totalParticipations = Participation::where('status', 'accepted')->count();
        $averageParticipantsPerConversation = $activeConversations > 0
            ? round($totalParticipations / $activeConversations, 1)
            : 0;

        $averageRating = Feedback::avg('rating') ?? 0;

        return [
            Stat::make('Total Usuarios', $totalUsers)
                ->description($newUsersThisMonth . ' nuevos este mes')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Usuarios Activos', $activeUsers)
                ->description(round(($activeUsers / max($totalUsers, 1)) * 100, 1) . '% del total')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Conversaciones', $totalConversations)
                ->description($newConversationsThisMonth . ' nuevas este mes')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),

            Stat::make('Conversaciones Activas', $activeConversations)
                ->description(round(($activeConversations / max($totalConversations, 1)) * 100, 1) . '% del total')
                ->descriptionIcon('heroicon-m-fire')
                ->color('warning'),

            Stat::make('Participaciones Totales', $totalParticipations)
                ->description('Promedio: ' . $averageParticipantsPerConversation . ' por conversación')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Valoración Promedio', number_format($averageRating, 2))
                ->description('De 5 estrellas')
                ->descriptionIcon('heroicon-m-star')
                ->color($averageRating >= 4 ? 'success' : ($averageRating >= 3 ? 'warning' : 'danger')),
        ];
    }
}
