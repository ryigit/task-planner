<?php

namespace App\Contract;

interface DeveloperSelectionStrategy
{
    public function selectDeveloper(array $task, array $remainingHours, array $developers): ?string;
}

