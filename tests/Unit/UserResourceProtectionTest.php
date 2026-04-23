<?php

namespace Tests\Unit;

use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use Tests\TestCase;

class UserResourceProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_cannot_be_demoted_from_is_admin(): void
    {
        $superAdmin = User::factory()->create([
            'email' => env('SEED_ADMIN_EMAIL', 'admin@admin.com'),
            'is_admin' => true,
        ]);

        $page = new EditUser();
        $reflection = new ReflectionClass($page);

        $recordProperty = $reflection->getProperty('record');
        $recordProperty->setAccessible(true);
        $recordProperty->setValue($page, $superAdmin);

        $method = $reflection->getMethod('mutateFormDataBeforeSave');
        $method->setAccessible(true);

        $data = $method->invoke($page, [
            'name' => 'Super Admin',
            'email' => $superAdmin->email,
            'is_admin' => false,
        ]);

        $this->assertTrue($data['is_admin']);
    }

    public function test_super_admin_cannot_be_deleted(): void
    {
        $superAdmin = User::factory()->create([
            'email' => env('SEED_ADMIN_EMAIL', 'admin@admin.com'),
            'is_admin' => true,
        ]);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $superAdmin->delete();
    }
}
