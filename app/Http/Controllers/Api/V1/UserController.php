<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\RolesEnum;
use Illuminate\Http\JsonResponse;

class UserController extends BaseApiController {
    /**
     * Get user information and permissions.
     *
     * @return JsonResponse
     */
    public function userInfo(): JsonResponse {
        $user = auth()->user();

        $permissions = [];

        if ($user->hasRole([RolesEnum::ADMINISTRATOR->value, RolesEnum::USER_MANAGER->value])) {
            $permissions['dashboard'] = true;
        }

        return $this->success([
            'user' => [
                'name' => $user->name,
            ],
            'permissions' => $permissions,
        ]);
    }
}
