<?php

namespace App\Services\User;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Support\Collection;

class UserService {
    public function create(array $data): User {
        $role = $data['role'] ?? RolesEnum::REGISTERED_USER->value;
        unset($data['role']);

        $user = User::create($data);
        $user->syncRoles([$role]);

        return $user;
    }

    public function update(User $user, array $data): User {
        $role = $data['role'] ?? $user->roles->first()?->name;
        unset($data['role']);

        $user->update($data);
        $user->syncRoles([$role]);

        return $user;
    }

    public function delete(User $user): bool {
        return $user->delete();
    }

    public function getAllUsers(): Collection {
        return User::withTrashed()->get();
    }

    public function restore(User $user): User {
        $user->restore();

        return $user;
    }
}
