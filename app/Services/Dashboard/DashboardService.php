<?php

namespace App\Services\Dashboard;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService {
    public function getStats(): array {
        return [
            'users' => [
                'total' => $this->getTotalUsers(),
                'active' => $this->getActiveUsers(),
                'deleted' => $this->getDeletedUsers(),
                'by_role' => $this->getUsersByRole(),
                'recent' => [
                    'week' => $this->getRecentUsers(7),
                    'month' => $this->getRecentUsers(30),
                ],
            ],
            'recent_users' => $this->getRecentUsersList(),
        ];
    }

    private function getTotalUsers(): int {
        return User::withTrashed()->count();
    }

    private function getActiveUsers(): int {
        return User::count();
    }

    private function getDeletedUsers(): int {
        return User::onlyTrashed()->count();
    }

    private function getUsersByRole(): array {
        return collect(RolesEnum::cases())->mapWithKeys(function (RolesEnum $role) {
            return [
                $role->value => User::role($role->value)->count(),
            ];
        })->toArray();
    }

    private function getRecentUsers(int $days): int {
        return User::where('created_at', '>=', now()->subDays($days))->count();
    }

    private function getRecentUsersList(): Collection {
        return User::with('roles')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($user) {
                $role = $user->roles->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'role' => $role ? RolesEnum::from($role->name)->value : null,
                ];
            });
    }
}
