<?php

use App\Models\User;
use App\Models\Task;
use Livewire\Volt\Volt;

test('tasks component can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertOk()
        ->assertSeeVolt('tasks');
});

test('tasks can be created', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Volt::test('tasks')
        ->set('title', 'New Task')
        ->call('addTask');

    $this->assertDatabaseHas('tasks', [
        'user_id' => $user->id,
        'title' => 'New Task',
        'is_completed' => false,
    ]);
});

test('tasks can be toggled', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Task to toggle',
        'is_completed' => false,
    ]);

    $this->actingAs($user);

    Volt::test('tasks')
        ->call('toggleTask', $task->id);

    $this->assertTrue($task->refresh()->is_completed);

    Volt::test('tasks')
        ->call('toggleTask', $task->id);

    $this->assertFalse($task->refresh()->is_completed);
});

test('users cannot toggle other users tasks', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $otherUser->id,
        'is_completed' => false,
    ]);

    $this->actingAs($user);

    Volt::test('tasks')
        ->call('toggleTask', $task->id)
        ->assertStatus(403);

    $this->assertFalse($task->refresh()->is_completed);
});

test('tasks can be deleted', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'title' => 'Task to delete',
    ]);

    $this->actingAs($user);

    Volt::test('tasks')
        ->call('deleteTask', $task->id);

    $this->assertDatabaseMissing('tasks', [
        'id' => $task->id,
    ]);
});

test('users cannot delete other users tasks', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $this->actingAs($user);

    Volt::test('tasks')
        ->call('deleteTask', $task->id)
        ->assertStatus(403);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
    ]);
});
