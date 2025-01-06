<?php

namespace App\Contract;

interface AssignmentStrategy
{
    public function assignTasks(array $tasks, array $developers): array;
}
