<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Volt;

test('profile update executes expected number of queries when email is unchanged', function () {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $this->actingAs($user);

    DB::enableQueryLog();

    $component = Volt::test('profile.update-profile-information-form')
        ->set('name', 'Updated Name')
        ->set('email', 'original@example.com')
        ->call('updateProfileInformation');

    $queries = DB::getQueryLog();

    expect(count($queries))->toBe(1);
});
