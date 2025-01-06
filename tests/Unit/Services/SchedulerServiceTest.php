<?php

namespace Feature\Services;

use App\Models\Developer;
use App\Models\Task;
use App\Services\SchedulerService;
use App\Contract\AssignmentStrategy;
use App\Contract\DeveloperSelectionStrategy;
use PHPUnit\Framework\MockObject\Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SchedulerServiceTest extends TestCase
{
    use RefreshDatabase;

    private SchedulerService $schedulerService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->assignmentStrategyMock = $this->createMock(AssignmentStrategy::class);
        $this->developerSelectionStrategyMock = $this->createMock(DeveloperSelectionStrategy::class);

        $this->schedulerService = new SchedulerService(
            $this->assignmentStrategyMock
        );
    }

    #[Test]
    public function testCalculateSchedule()
    {
        $developers = Developer::factory()->count(5)->create(); // 5 developers
        $tasks = Task::factory()->count(10)->create(); // 10 tasks

        $this->assignmentStrategyMock
            ->method('assignTasks')
            ->willReturn([
                'assignments' => [
                    'DEV1' => [],
                    'DEV2' => [
                        [
                            'id' => 1,
                            'duration' => 2,
                            'complexity' => 4,
                        ],
                        [
                            'id' => 2,
                            'duration' => 3,
                            'complexity' => 3,
                        ],
                    ]
                  ],
                  'remaining' => []
                ]
            );

        $this->developerSelectionStrategyMock
            ->method('selectDeveloper')
            ->willReturn($developers->pluck('name')->first());

        $developerData = $developers->mapWithKeys(function($developer) {
            return [
                $developer->name => [
                    'weekly_hours' => 40,
                    'efficiency_rate' => 1,
                ]
            ];
        })->toArray();

        $taskData = $tasks->map(function($task) {
            return [
                'id' => $task->id,
                'complexity' => 3,
                'duration' => 5,
            ];
        })->toArray();

        $schedule = $this->schedulerService->calculateSchedule($developerData, $taskData);

        $this->assertIsArray($schedule);
        $this->assertArrayHasKey('schedule', $schedule);
        $this->assertArrayHasKey('total_weeks', $schedule);

        $this->assertNotEmpty($schedule['schedule']);
        $this->assertGreaterThan(0, $schedule['total_weeks']);
    }
}
