<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentUserAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_can_access_the_filament_panel(): void
    {
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'is_admin' => true,
            'email' => 'admin-access@example.com',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_non_admin_user_cannot_access_the_filament_panel(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'is_admin' => false,
            'email' => 'member-access@example.com',
        ]);

        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(403);
    }
}
