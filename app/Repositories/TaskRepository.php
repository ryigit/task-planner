<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function deleteAll(): void
    {
        Task::truncate(); // Efficiently deletes all rows without triggering events.
    }

    public function saveMany(array $tasks): void
    {
        foreach ($tasks as $task) {
            Task::create([
                'name' => $task['name'],
                'complexity' => $task['complexity'],
                'duration' => $task['duration'],
                'provider_id' => $task['provider_id'],
                'source_id' => $task['source_id'],
                'original_payload' => $task['original_payload'],
            ]);
        }
    }

    public function getAll(): array
    {
        return Task::all()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'duration' => $task->duration,
                    'complexity' => $task->complexity,
                ];
            })
            ->toArray();
    }
}

