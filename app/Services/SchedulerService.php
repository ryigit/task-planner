<?php

namespace App\Services;

use App\Models\Developer;
use App\Models\Task;

class SchedulerService
{
    /**
     * Calculate schedule for all tasks
     */
    public function calculateSchedule(array $developers, array $tasks): array
    {
        $schedule = [];
        $currentWeek = 1;
        $remainingTasks = $tasks;

        while (count($remainingTasks) > 0) {
            $weeklySchedule = $this->assignTasksForWeek($remainingTasks, $developers);
            $schedule[$currentWeek] = $weeklySchedule['assignments'];
            $remainingTasks = $weeklySchedule['remaining'];
            $currentWeek++;
        }

        return [
            'schedule' => $schedule,
            'total_weeks' => $currentWeek - 1
        ];
    }

    /**
     * Assign tasks for a single week
     */
    private function assignTasksForWeek(array &$tasks, array $developers): array
    {
        $assignments = array_fill_keys(array_keys($developers), []);
        $remainingHours = array_map(function ($dev) {
            return $dev['weekly_hours'];
        }, $developers);

        foreach ($tasks as $index => $task) {
            $bestDev = $this->findBestDeveloper($task, $remainingHours, $developers);

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

    /**
     * Find the best developer for a task based on efficiency and available hours
     */
    private function findBestDeveloper(array $task, array $remainingHours, array $developers): ?string
    {
        $bestDev = null;
        $bestTime = PHP_FLOAT_MAX;

        foreach ($developers as $devName => $developer) {
            if ($remainingHours[$devName] >= $task['duration']) {
                $timeToComplete = $task['duration'] * $task['complexity'] / $developer['efficiency_rate'];

                if ($timeToComplete < $bestTime) {
                    $bestTime = $timeToComplete;
                    $bestDev = $devName;
                }
            }
        }

        return $bestDev;
    }
}
