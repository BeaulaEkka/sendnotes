<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;

test('registration validation stops after first failure when bail is used', function () {
    // Attempt to register with an invalid email that would also trigger a unique check if not for bail
    // But first we need a user to trigger the unique failure
    User::factory()->create(['email' => 'test@example.com']);

    DB::enableQueryLog();

    $component = Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'not-an-email') // This should fail the 'email' rule
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register');

    $component->assertHasErrors(['email']);

    $queries = DB::getQueryLog();

    // If 'bail' was working and 'email' rule failed, it SHOULD NOT have executed the unique check query.
    // The unique check query would look like "select count(*) as aggregate from "users" where "email" = ?"

    $hasUniqueQuery = collect($queries)->contains(function ($query) {
        return str_contains($query['query'], 'select count(*)');
    });

    expect($hasUniqueQuery)->toBeFalse();
});

test('profile update validation stops after first failure when bail is used', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);
    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($user);

    DB::enableQueryLog();

    $component = Volt::test('profile.update-profile-information-form')
        ->set('name', 'New Name')
        ->set('email', 'not-an-email') // This should fail the 'email' rule
        ->call('updateProfileInformation');

    $component->assertHasErrors(['email']);

    $queries = DB::getQueryLog();

    $hasUniqueQuery = collect($queries)->contains(function ($query) {
        return str_contains($query['query'], 'select count(*)');
    });

    expect($hasUniqueQuery)->toBeFalse();
});
