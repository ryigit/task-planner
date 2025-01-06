<?php

namespace App\Services\Scheduling\Strategies;

use App\Contract\DeveloperSelectionStrategy;

class EfficiencyBasedSelectionStrategy implements DeveloperSelectionStrategy
{
    public function selectDeveloper(array $task, array $remainingHours, array $developers): ?string
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

