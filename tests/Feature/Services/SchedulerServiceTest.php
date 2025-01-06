<?php

namespace Feature\Services;

use App\Models\Developer;
use App\Models\Task;
use App\Services\SchedulerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SchedulerServiceTest extends TestCase
{
    use RefreshDatabase;

    private SchedulerService $schedulerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->schedulerService = new SchedulerService();
    }

    #[Test]
    public function testCalculateSchedule()
    {
        Developer::factory()->create();
        Task::factory()->create();

        $schedule = $this->schedulerService->calculateSchedule();

        $this->assertIsArray($schedule);
        $this->assertArrayHasKey('schedule', $schedule);
        $this->assertArrayHasKey('total_weeks', $schedule);
    }
}
