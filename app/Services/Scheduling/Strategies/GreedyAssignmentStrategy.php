<?php

namespace App\Services\Scheduling\Strategies;

use App\Contract\AssignmentStrategy;
use App\Contract\DeveloperSelectionStrategy;

class GreedyAssignmentStrategy implements AssignmentStrategy
{
    public function __construct(private readonly DeveloperSelectionStrategy $selectionStrategy) {}

    public function assignTasks(array $tasks, array $developers): array
    {
        $assignments = array_fill_keys(array_keys($developers), []);
        $remainingHours = array_map(fn($dev) => $dev['weekly_hours'], $developers);

        foreach ($tasks as $index => $task) {
            $bestDev = $this->selectionStrategy->selectDeveloper($task, $remainingHours, $developers);

            if ($bestDev) {
                $assignments[$bestDev][] = $task;
                $remainingHours[$bestDev] -= $task['duration'];
                unset($tasks[$index]);
            }
        }

        return [
            'assignments' => $assignments,
            'remaining' => $tasks
        ];
    }
}
