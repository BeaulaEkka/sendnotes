<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;
use Tests\TestCase;

class ConfirmPasswordQueryTest extends TestCase
{
    public function test_confirm_password_performing_no_unnecessary_user_query()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        DB::enableQueryLog();

        $component = Volt::test('pages.auth.confirm-password')
            ->set('password', 'password');

        $component->call('confirmPassword');

        $queries = DB::getQueryLog();

        $userQueries = array_filter($queries, function ($query) {
            return str_contains($query['query'], 'select * from "users"') || str_contains($query['query'], 'select * from `users`');
        });

        // It should be 0 now because 'current_password' rule uses the already authenticated user.
        $this->assertCount(0, $userQueries, 'Redundant user lookup query detected in password confirmation.');
    }
}
