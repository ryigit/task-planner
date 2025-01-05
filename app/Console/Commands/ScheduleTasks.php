<?php

namespace App\Console\Commands;

use App\Models\Developer;
use App\Models\Task;
use App\Services\SchedulerService;
use App\Services\TaskProviderService;
use Exception;
use Illuminate\Console\Command;

class ScheduleTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch tasks from all providers and create a work schedule';

    public function __construct(
        private readonly TaskProviderService $taskProviderService,
        private readonly SchedulerService $schedulerService
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting task orchestration...');

        try {
            // Step 1: Fetch tasks
            $this->fetchTasks();

            if ($this->option('fetch-only')) {
                return 0;
            }

            // Step 2: Create schedule
            $this->createSchedule();

            // Step 3: Display results
            $this->displayResults();

        } catch (\Exception $e) {
            $this->error("Error during orchestration: " . $e->getMessage());
            return 1;
        }
    }

    private function fetchTasks(): void
    {
        $this->info('Fetching tasks from providers...');

        // Clear existing unassigned tasks
        Task::whereNull('assigned_to')->delete();

        $tasks = $this->taskProviderService->fetchAllTasks();

        $count = count($tasks);
        $this->info("Retrieved {$count} tasks from providers");

        // Display task summary
        $this->table(
            ['Provider', 'Count'],
            collect($tasks)
                ->groupBy('provider')
                ->map(fn($tasks, $provider) => [$provider, count($tasks)])
                ->values()
                ->toArray()
        );
    }

    /**
     * @throws Exception
     */
    private function createSchedule(): void
    {
        $this->info('Creating work schedule...');

        // Verify we have active developers
        $developerCount = Developer::where('is_active', true)->count();
        if ($developerCount === 0) {
            throw new Exception('No active developers found in the system');
        }

        $schedule = $this->schedulerService->calculateSchedule();

        $this->info("Schedule created for {$schedule['total_weeks']} weeks");
    }

    private function displayResults(): void
    {
        $this->info('Final Schedule Summary:');

        // Get schedule summary by developer
        $summary = Task::whereNotNull('assigned_to')
            ->selectRaw('assigned_to, COUNT(*) as task_count, SUM(duration) as total_hours')
            ->groupBy('assigned_to')
            ->get();

        // Display developer workload
        $this->table(
            ['Developer', 'Tasks', 'Total Hours'],
            $summary->map(fn($item) => [
                $item->assigned_to,
                $item->task_count,
                $item->total_hours
            ])->toArray()
        );

        // Display weekly breakdown
        $weeklyBreakdown = Task::whereNotNull('week_number')
            ->selectRaw('week_number, COUNT(*) as task_count')
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get();

        $this->table(
            ['Week', 'Tasks'],
            $weeklyBreakdown->map(fn($item) => [
                $item->week_number,
                $item->task_count
            ])->toArray()
        );
    }

}
