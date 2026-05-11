<?php

use App\Models\Task;
use function Livewire\Volt\{state, computed};

state(['title' => '']);

$tasks = computed(fn () => auth()->user()->tasks()->latest()->get());

$addTask = function () {
    $this->validate(['title' => 'required|string|max:255']);

    auth()->user()->tasks()->create([
        'title' => $this->title,
    ]);

    $this->title = '';
};

$toggleTask = function (Task $task) {
    if ($task->user_id !== auth()->id()) {
        abort(403);
    }

    $task->update([
        'is_completed' => ! $task->is_completed,
    ]);
};

$deleteTask = function (Task $task) {
    if ($task->user_id !== auth()->id()) {
        abort(403);
    }

    $task->delete();
};

?>

<div>
    <form wire:submit="addTask" class="mb-4 flex">
        <x-text-input wire:model="title" placeholder="New Task..." class="flex-1 mr-2" />
        <x-primary-button>Add</x-primary-button>
    </form>

    <ul class="space-y-2">
        @foreach ($this->tasks as $task)
            <li class="flex items-center justify-between p-2 bg-white dark:bg-gray-800 rounded shadow">
                <div class="flex items-center">
                    <input type="checkbox" wire:click="toggleTask({{ $task->id }})" {{ $task->is_completed ? 'checked' : '' }} class="mr-2 rounded">
                    <span class="{{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                        {{ $task->title }}
                    </span>
                </div>
                <button wire:click="deleteTask({{ $task->id }})" class="text-red-500 hover:text-red-700">
                    &times;
                </button>
            </li>
        @endforeach
    </ul>
</div>
