<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function deleteAll(): void
    {
        Task::truncate(); // Efficiently deletes all rows without triggering events.
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

