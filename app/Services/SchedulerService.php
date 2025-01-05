<?php

namespace App\Services;

use App\Models\Developer;
use App\Models\Task;

class SchedulerService
{
    /**
     * Calculate schedule for all unassigned tasks
     */
    public function calculateSchedule(): array
    {
        // Get all active developers
        $developers = Developer::where('is_active', true)
            ->get()
            ->keyBy('name')
            ->map(function ($developer) {
                return [
                    'efficiency_rate' => $developer->efficiency_rate,
                    'weekly_hours' => $developer->weekly_hours,
                ];
            })
            ->toArray();

        // Get all unassigned tasks
        $tasks = Task::whereNull('assigned_to')
            ->whereNull('week_number')
            ->get()
            ->map(function ($task) {
                return [
                    'id' => $task->id,
                    'duration' => $task->duration,
                    'difficulty' => $task->difficulty,
                ];
            })
            ->toArray();

        $schedule = [];
        $currentWeek = 1;
        $remainingTasks = $tasks;

        while (count($remainingTasks) > 0) {
            $weeklySchedule = $this->assignTasksForWeek($remainingTasks, $developers);
            $schedule[$currentWeek] = $weeklySchedule['assignments'];
            $remainingTasks = $weeklySchedule['remaining'];
            $currentWeek++;

            // Update tasks in database with assignments
            foreach ($weeklySchedule['assignments'] as $developerName => $assignedTasks) {
                foreach ($assignedTasks as $task) {
                    Task::where('id', $task['id'])->update([
                        'assigned_to' => $developerName,
                        'week_number' => $currentWeek - 1
                    ]);
                }
            }
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
                $timeToComplete = $task['duration'] * $task['difficulty'] / $developer['efficiency_rate'];

                if ($timeToComplete < $bestTime) {
                    $bestTime = $timeToComplete;
                    $bestDev = $devName;
                }
            }
        }

        return $bestDev;
    }
}
