<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase {
    use RefreshDatabase;

    private User $admin;

    private User $userManager;

    private User $regularUser;

    protected function setUp(): void {
        parent::setUp();

        // Run the role seeder first
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);

        $password = 'password';

        // Create admin user
        $this->admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make($password),
            ]
        );
        $this->admin->assignRole(RolesEnum::ADMINISTRATOR->value);

        // Create user manager
        $this->userManager = User::updateOrCreate(
            ['email' => 'user_manager@example.com'],
            [
                'name' => 'User Manager',
                'password' => Hash::make($password),
            ]
        );
        $this->userManager->assignRole(RolesEnum::USER_MANAGER->value);

        // Create registered user
        $this->regularUser = User::updateOrCreate(
            ['email' => 'registered_user@example.com'],
            [
                'name' => 'Registered User',
                'password' => Hash::make($password),
            ]
        );
        $this->regularUser->assignRole(RolesEnum::REGISTERED_USER->value);
    }

    public function test_index_shows_users_list_to_authorized_users(): void {
        $response = $this->actingAs($this->admin)->get(route('users.index'));
        $response->assertStatus(200);
        $response->assertInertia(
            fn ($page) => $page
                ->component('users/index')
                ->has('users')
        );

        $response = $this->actingAs($this->userManager)->get(route('users.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->regularUser)->get(route('users.index'));
        $response->assertStatus(403);
    }

    public function test_create_shows_form_to_authorized_users(): void {
        $response = $this->actingAs($this->admin)->get(route('users.create'));
        $response->assertStatus(200);
        $response->assertInertia(
            fn ($page) => $page
                ->component('users/create')
                ->has('roles')
        );

        $response = $this->actingAs($this->userManager)->get(route('users.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->regularUser)->get(route('users.create'));
        $response->assertStatus(403);
    }

    public function test_store_creates_new_user(): void {
        $this->actingAs($this->admin)->get(route('users.create'));

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => RolesEnum::REGISTERED_USER->value,
            '_token' => session('_token'),
        ];

        $response = $this->post(route('users.store'), $userData);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue($user->hasRole(RolesEnum::REGISTERED_USER->value));
    }

    public function test_edit_shows_form_to_authorized_users(): void {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('users.edit', $user));
        $response->assertStatus(200);
        $response->assertInertia(
            fn ($page) => $page
                ->component('users/edit')
                ->has('user')
                ->has('roles')
        );

        $response = $this->actingAs($this->userManager)->get(route('users.edit', $user));
        $response->assertStatus(200);

        $response = $this->actingAs($this->regularUser)->get(route('users.edit', $user));
        $response->assertStatus(403);
    }

    public function test_update_modifies_existing_user(): void {
        $user = User::factory()->create();

        $this->actingAs($this->admin)->get(route('users.edit', $user));
        $response = $this->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => RolesEnum::REGISTERED_USER->value,
            '_token' => session('_token'),
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_destroy_deletes_user(): void {
        $user = User::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('users.index')); // Get page first to establish session

        $response = $this->delete(route('users.destroy', $user), [
            '_token' => session('_token'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_manager_cannot_modify_admin(): void {
        // Create an admin user to be modified
        $adminToModify = User::factory()->create();
        $adminToModify->syncRoles([RolesEnum::ADMINISTRATOR->value]);

        // Get page first to establish session
        $this->actingAs($this->userManager)
            ->get(route('users.edit', $adminToModify));  // Get the edit page specifically

        // Try to modify the admin as user manager
        $response = $this->put(route('users.update', $adminToModify), [
            'name' => 'Updated Name',
            'email' => $adminToModify->email,
            'role' => RolesEnum::ADMINISTRATOR->value,
            '_token' => session('_token'),
        ]);

        $response->assertStatus(403);
    }
}
