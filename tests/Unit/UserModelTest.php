<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_is_hashed_when_user_is_saved(): void
    {
        $user = User::query()->create([
            'name' => 'Hash Check',
            'email' => 'hash-check@example.com',
            'password' => 'secret-password',
            'is_admin' => false,
        ]);

        $this->assertNotSame('secret-password', $user->password);
        $this->assertTrue(Hash::check('secret-password', $user->password));
    }

    public function test_can_access_panel_only_when_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $member = User::factory()->create(['is_admin' => false]);

        $this->assertTrue($admin->canAccessPanel(app(\Filament\Panel::class)));
        $this->assertFalse($member->canAccessPanel(app(\Filament\Panel::class)));
    }
}
