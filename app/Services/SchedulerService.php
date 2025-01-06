<?php

namespace App\Services;

use App\Contract\AssignmentStrategy;

class SchedulerService
{
    public function __construct(
        private readonly AssignmentStrategy $assignmentStrategy,
    ) {}

    public function calculateSchedule(array $developers, array $tasks): array
    {
        $schedule = [];
        $currentWeek = 1;
        $remainingTasks = $tasks;

        while (count($remainingTasks) > 0) {
            $weeklySchedule = $this->assignmentStrategy->assignTasks($remainingTasks, $developers);
            $schedule[$currentWeek] = $weeklySchedule['assignments'];
            $remainingTasks = $weeklySchedule['remaining'];
            $currentWeek++;
        }

        return [
            'schedule' => $schedule,
            'total_weeks' => $currentWeek - 1
        ];
    }
}
