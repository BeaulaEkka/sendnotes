<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Volt\Volt;

test('confirm password avoids redundant database queries', function () {
    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    Auth::login($user);

    DB::enableQueryLog();

    Volt::test('pages.auth.confirm-password')
        ->set('password', 'password')
        ->call('confirmPassword');

    $queries = DB::getQueryLog();

    // We expect 0 queries if optimized, or at least fewer than before.
    // Currently, it likely does 1 query to find the user by email.
    expect(count($queries))->toBeLessThan(1);
});
