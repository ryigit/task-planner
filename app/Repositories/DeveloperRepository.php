<?php

namespace App\Repositories;

namespace App\Repositories;

use App\Models\Developer;

class DeveloperRepository
{
    public function getActiveDevelopers(): array
    {
        return Developer::where('is_active', true)
            ->get()
            ->keyBy('name')
            ->map(function ($developer) {
                return [
                    'efficiency_rate' => $developer->efficiency_rate,
                    'weekly_hours' => $developer->weekly_hours,
                ];
            })
            ->toArray();
    }

    public function countActiveDevelopers(): int
    {
        return Developer::where('is_active', true)->count();
    }
}

