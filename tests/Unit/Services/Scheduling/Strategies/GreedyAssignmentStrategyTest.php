<?php

namespace Feature\Services\Scheduling\Strategies;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Services\Scheduling\Strategies\GreedyAssignmentStrategy;
use App\Contract\DeveloperSelectionStrategy;

class GreedyAssignmentStrategyTest extends TestCase
{
    #[Test]
    public function assignsTasksToDevelopers()
    {
        $selectionStrategy = $this->createMock(DeveloperSelectionStrategy::class);
        $selectionStrategy->method('selectDeveloper')
            ->willReturnOnConsecutiveCalls('Dev1', 'Dev2', 'Dev1');

        $strategy = new GreedyAssignmentStrategy($selectionStrategy);

        $tasks = [
            ['id' => 1, 'duration' => 10],
            ['id' => 2, 'duration' => 5],
            ['id' => 3, 'duration' => 15]
        ];

        $developers = [
            'Dev1' => ['weekly_hours' => 20],
            'Dev2' => ['weekly_hours' => 10]
        ];

        $result = $strategy->assignTasks($tasks, $developers);

        $this->assertEquals([
            'assignments' => [
                'Dev1' => [
                    ['id' => 1, 'duration' => 10],
                    ['id' => 3, 'duration' => 15]
                ],
                'Dev2' => [
                    ['id' => 2, 'duration' => 5]
                ]
            ],
            'remaining' => []
        ], $result);
    }

    #[Test]
    public function handlesNoTasks()
    {
        $selectionStrategy = $this->createMock(DeveloperSelectionStrategy::class);
        $strategy = new GreedyAssignmentStrategy($selectionStrategy);

        $tasks = [];
        $developers = [
            'Dev1' => ['weekly_hours' => 20],
            'Dev2' => ['weekly_hours' => 10]
        ];

        $result = $strategy->assignTasks($tasks, $developers);

        $this->assertEquals([
            'assignments' => [
                'Dev1' => [],
                'Dev2' => []
            ],
            'remaining' => []
        ], $result);
    }

    #[Test]
    public function handlesNoDevelopers()
    {
        $selectionStrategy = $this->createMock(DeveloperSelectionStrategy::class);
        $strategy = new GreedyAssignmentStrategy($selectionStrategy);

        $tasks = [
            ['id' => 1, 'duration' => 10],
            ['id' => 2, 'duration' => 5]
        ];
        $developers = [];

        $result = $strategy->assignTasks($tasks, $developers);

        $this->assertEquals([
            'assignments' => [],
            'remaining' => $tasks
        ], $result);
    }

    // toDo: Fix this case
    #[Test]
    public function handlesTasksExceedingDeveloperHours()
    {
        $selectionStrategy = $this->createMock(DeveloperSelectionStrategy::class);
        $selectionStrategy->method('selectDeveloper')
            ->willReturnOnConsecutiveCalls('Dev1', 'Dev2', null);

        $strategy = new GreedyAssignmentStrategy($selectionStrategy);

        $tasks = [
            ['id' => 1, 'duration' => 10],
            ['id' => 2, 'duration' => 5],
            ['id' => 3, 'duration' => 15]
        ];

        $developers = [
            'Dev1' => ['weekly_hours' => 10],
            'Dev2' => ['weekly_hours' => 5]
        ];

        $result = $strategy->assignTasks($tasks, $developers);

        $this->assertEquals([
            'assignments' => [
                'Dev1' => [
                    ['id' => 1, 'duration' => 10]
                ],
                'Dev2' => [
                    ['id' => 2, 'duration' => 5]
                ]
            ],
            'remaining' => [
                2 =>['id' => 3, 'duration' => 15]
            ]
        ], $result);
    }
}
