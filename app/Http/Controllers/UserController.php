<?php

namespace App\Http\Controllers;

use App\Enums\RolesEnum;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class UserController extends Controller {
    use AuthorizesRequests;

    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Display a listing of users.
     */
    public function index() {
        $this->authorize('viewAny', User::class);

        return Inertia::render('users/index', [
            'users' => $this->userService->getAllUsers(),
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create() {
        $this->authorize('create', User::class);

        return Inertia::render('users/create', [
            'roles' => $this->getRolesForForm(),
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(UserStoreRequest $request) {
        $this->authorize('create', User::class);

        $this->userService->create($request->validated());

        return redirect()->route('users.index');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user) {
        $this->authorize('view', $user);

        return Inertia::render('users/show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user) {
        $this->authorize('update', $user);

        return Inertia::render('users/edit', [
            'user' => $user,
            'roles' => $this->getRolesForForm(),
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(UserUpdateRequest $request, User $user) {
        $this->authorize('update', $user);

        $this->userService->update($user, $request->validated());

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user) {
        $this->authorize('delete', $user);

        $this->userService->delete($user);

        return redirect()->route('users.index')
            ->with('success', __('users.messages.deleted'));
    }

    /**
     * Restore the specified user.
     */
    public function restore(User $user) {
        $this->authorize('restore', $user);

        $this->userService->restore($user);

        return redirect()->route('users.index')
            ->with('success', __('users.messages.restored'));
    }

    /**
     * Get the roles for the form.
     * If user is not admin, filter available roles.
     *
     * @return array<RolesEnum, string>
     */
    private function getRolesForForm(): array {
        $roles = collect(RolesEnum::cases());

        // If user is not admin, filter available roles
        if (!auth()->user()->hasRole(RolesEnum::ADMINISTRATOR->value)) {
            $roles = $roles->filter(
                fn (RolesEnum $role) => in_array($role->value, [
                    RolesEnum::USER_MANAGER->value,
                    RolesEnum::REGISTERED_USER->value,
                ])
            );
        }

        return $roles->map(function (RolesEnum $role) {
            return [
                'name' => $role->value,
                'label' => 'roles.' . str_replace('-', '_', $role->value),
            ];
        })->values()->all();
    }
}
