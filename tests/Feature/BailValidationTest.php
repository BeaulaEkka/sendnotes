<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;

test('profile update validation behavior with bail on email format', function () {
    $user = User::factory()->create([
        'email' => 'original@example.com',
    ]);
    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($user);

    DB::enableQueryLog();

    try {
        Volt::test('profile.update-profile-information-form')
            ->set('email', 'invalid-email') // fails 'email'
            ->call('updateProfileInformation');
    } catch (\Illuminate\Validation\ValidationException $e) {
    }

    $queries = DB::getQueryLog();

    $uniqueQueries = array_filter($queries, function ($query) {
        return str_contains($query['query'], 'select count(*)');
    });

    // Should be 0 now because 'email' rule failed and we have 'bail'
    expect(count($uniqueQueries))->toBe(0);
});

test('profile update validation behavior with bail on other fields', function () {
    $user = User::factory()->create([
        'email' => 'original@example.com',
    ]);
    User::factory()->create(['email' => 'taken@example.com']);

    $this->actingAs($user);

    DB::enableQueryLog();

    try {
        Volt::test('profile.update-profile-information-form')
            ->set('name', str_repeat('a', 300)) // too long, fails 'max:255'
            ->set('email', 'new@example.com')
            ->call('updateProfileInformation');
    } catch (\Illuminate\Validation\ValidationException $e) {
    }

    $queries = DB::getQueryLog();

    $uniqueQueries = array_filter($queries, function ($query) {
        return str_contains($query['query'], 'select count(*)');
    });

    // Actually, bail is PER attribute. So 'name' failing doesn't stop 'email' validation.
    // BUT 'email' failing stops 'unique' check on 'email'.
    // If I want to verify bail on 'email', I need to make 'email' fail first.
    // I already did that in the first test.

    // In this test, email is valid format, so unique check STILL RUNS.
    // This is expected as 'bail' is per-attribute.
    expect(count($uniqueQueries))->toBeGreaterThan(0);
});

test('registration validation behavior with bail', function () {
    DB::enableQueryLog();

    try {
        Volt::test('pages.auth.register')
            ->set('email', 'invalid-email')
            ->call('register');
    } catch (\Illuminate\Validation\ValidationException $e) {
    }

    $queries = DB::getQueryLog();
    $uniqueQueries = array_filter($queries, function ($query) {
        return str_contains($query['query'], 'select count(*)');
    });

    expect(count($uniqueQueries))->toBe(0);
});
