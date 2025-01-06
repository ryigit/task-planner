<?php

namespace App\Services;

use Illuminate\Console\Command;

class SchedulePresenterService
{
    public function present(array $scheduleData, Command $command): void
    {
        $schedule = $scheduleData['schedule'];
        $totalWeeks = $scheduleData['total_weeks'];

        $command->info("Schedule created for {$totalWeeks} weeks");

        foreach ($schedule as $weekNumber => $developers) {
            $command->info("\n📅 Week {$weekNumber}");

            foreach ($developers as $developer => $tasks) {
                $command->info("\n👨‍💻 Developer: {$developer}");

                $taskTable = collect($tasks)->map(fn($task) => [
                    'Task ID' => $task['id'],
                    'Duration (hours)' => $task['duration'],
                    'Complexity' => $task['complexity'] ?? 'Not specified',
                ])->toArray();

                $command->table(['Task ID', 'Duration (hours)', 'Complexity'], $taskTable);

                $summary = [
                    'Total Tasks' => count($tasks),
                    'Total Hours' => collect($tasks)->sum('duration'),
                ];

                $command->info("Summary for {$developer}: " . json_encode($summary));
            }
        }
    }
}

