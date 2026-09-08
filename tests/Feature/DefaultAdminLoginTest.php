<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DefaultAdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_admin_user_can_login(): void
    {
        $this->seed();

        $this->assertTrue(Auth::attempt([
            'email' => 'admin@sekolah.test',
            'password' => 'password123',
        ]));

        $this->assertDatabaseHas('users', [
            'email' => 'admin@sekolah.test',
            'role' => 'admin',
        ]);
    }
}
