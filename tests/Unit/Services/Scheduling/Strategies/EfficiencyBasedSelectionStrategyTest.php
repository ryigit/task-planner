<?php

namespace Feature\Services\Scheduling\Strategies;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Services\Scheduling\Strategies\EfficiencyBasedSelectionStrategy;

class EfficiencyBasedSelectionStrategyTest extends TestCase
{
    #[Test]
    public function selectsDeveloperWithHighestEfficiency()
    {
        $strategy = new EfficiencyBasedSelectionStrategy();
        $task = ['duration' => 10, 'complexity' => 1.0];
        $remainingHours = ['Dev1' => 20, 'Dev2' => 20];
        $developers = [
            'Dev1' => ['efficiency_rate' => 1.0],
            'Dev2' => ['efficiency_rate' => 2.0]
        ];

        $result = $strategy->selectDeveloper($task, $remainingHours, $developers);

        $this->assertEquals('Dev2', $result);
    }

    #[Test]
    public function returnsNullWhenNoDeveloperHasEnoughHours()
    {
        $strategy = new EfficiencyBasedSelectionStrategy();
        $task = ['duration' => 10, 'complexity' => 1.0];
        $remainingHours = ['Dev1' => 5, 'Dev2' => 5];
        $developers = [
            'Dev1' => ['efficiency_rate' => 1.0],
            'Dev2' => ['efficiency_rate' => 2.0]
        ];

        $result = $strategy->selectDeveloper($task, $remainingHours, $developers);

        $this->assertNull($result);
    }

    #[Test]
    public function selectsDeveloperWithSufficientHoursAndLowestCompletionTime()
    {
        $strategy = new EfficiencyBasedSelectionStrategy();
        $task = ['duration' => 10, 'complexity' => 1.5];
        $remainingHours = ['Dev1' => 20, 'Dev2' => 20];
        $developers = [
            'Dev1' => ['efficiency_rate' => 1.0],
            'Dev2' => ['efficiency_rate' => 1.5]
        ];

        $result = $strategy->selectDeveloper($task, $remainingHours, $developers);

        $this->assertEquals('Dev2', $result);
    }

    #[Test]
    public function handlesEmptyDevelopersList()
    {
        $strategy = new EfficiencyBasedSelectionStrategy();
        $task = ['duration' => 10, 'complexity' => 1.0];
        $remainingHours = [];
        $developers = [];

        $result = $strategy->selectDeveloper($task, $remainingHours, $developers);

        $this->assertNull($result);
    }

    #[Test]
    public function handlesTaskWithZeroDuration()
    {
        $strategy = new EfficiencyBasedSelectionStrategy();
        $task = ['duration' => 0, 'complexity' => 1.0];
        $remainingHours = ['Dev1' => 20, 'Dev2' => 20];
        $developers = [
            'Dev1' => ['efficiency_rate' => 1.0],
            'Dev2' => ['efficiency_rate' => 2.0]
        ];

        $result = $strategy->selectDeveloper($task, $remainingHours, $developers);

        $this->assertEquals('Dev1', $result);
    }
}
