<?php

namespace Tests\Feature\Performance;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class LoginFormPerformanceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_form_is_functional()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Volt::test('pages.auth.login')
            ->set('form.email', 'test@example.com')
            ->set('form.password', 'password')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect();
    }

    /** @test */
    public function login_form_validation_fails_for_invalid_email()
    {
        $component = Volt::test('pages.auth.login')
            ->set('form.email', 'not-an-email')
            ->set('form.password', 'password')
            ->call('login');

        $component->assertHasErrors(['form.email']);
    }
}
