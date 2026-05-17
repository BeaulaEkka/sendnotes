<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;
use Tests\TestCase;

class BailValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_does_not_run_unique_query_on_registration_if_email_format_is_invalid()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        DB::enableQueryLog();

        $component = Volt::test('pages.auth.register')
            ->set('name', 'Test User')
            ->set('email', 'invalid-email') // fails 'email', should bail
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('register');

        $queries = DB::getQueryLog();

        $hasUniqueQuery = collect($queries)->contains(function ($query) {
            return str_contains(strtolower($query['query']), 'select count(*)');
        });

        $this->assertFalse($hasUniqueQuery, 'Expected unique query NOT to be executed because of bail rule on email');
    }

    /** @test */
    public function it_does_not_run_unique_query_on_profile_update_if_email_format_is_invalid()
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $this->actingAs($user);

        DB::enableQueryLog();

        $component = Volt::test('profile.update-profile-information-form')
            ->set('name', 'Updated Name')
            ->set('email', 'invalid-email') // fails 'email', should bail
            ->call('updateProfileInformation');

        $queries = DB::getQueryLog();

        $hasUniqueQuery = collect($queries)->contains(function ($query) {
            return str_contains(strtolower($query['query']), 'select count(*)');
        });

        $this->assertFalse($hasUniqueQuery, 'Expected unique query NOT to be executed because of bail rule on email');
    }
}
