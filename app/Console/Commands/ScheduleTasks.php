<?php

namespace App\Console\Commands;

use App\Repositories\DeveloperRepository;
use App\Repositories\TaskRepository;
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
    protected $signature = 'app:schedule-tasks {--fetch-only : Fetch and persist tasks without creating the schedule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch tasks from all providers and create a work schedule';

    public function __construct(
        private readonly TaskProviderService $taskProviderService,
        private readonly SchedulerService $schedulerService,
        private readonly DeveloperRepository $developerRepository,
        private readonly TaskRepository $taskRepository
    )
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting task orchestration...');

        try {
            $tasks = $this->taskProviderService->fetchAndPersistTasks();
            $this->info("Retrieved and saved " . count($tasks) . " tasks.");

            if ($this->option('fetch-only')) {
                $this->info('Fetch and persist completed. Skipping schedule creation.');
                return 0;
            }

            $schedule = $this->createSchedule();
            $this->displayResults($schedule);
        } catch (Exception $e) {
            $this->error("Error during orchestration: " . $e->getMessage());
            return 1;
        }
    }

    private function createSchedule(): array
    {
        $this->info('Creating work schedule...');

        $developerCount = $this->developerRepository->countActiveDevelopers();
        if ($developerCount === 0) {
            throw new Exception('No active developers found in the system');
        }

        $developers = $this->developerRepository->getActiveDevelopers();
        $tasks = $this->taskRepository->getAll();

        $schedule = $this->schedulerService->calculateSchedule($developers, $tasks);

        $this->info("Schedule created for {$schedule['total_weeks']} weeks");

        return $schedule;
    }

    private function displayResults(array $scheduleData): void
    {
        $schedule = $scheduleData['schedule'];
        $this->info('Final Schedule Summary:');

        foreach ($schedule[1] as $developer => $tasks) {
            $this->info("\n📋 Developer: $developer");

            if (empty($tasks)) {
                $this->warn('No tasks assigned');
                continue;
            }

            $this->table(
                ['Task ID', 'Duration (hours)', 'Complexity'],
                collect($tasks)->map(fn($task) => [
                    $task['id'],
                    $task['duration'],
                    $task['complexity'] ?? 'Not specified'
                ])->toArray()
            );

            //toDo: Calculate total hours
            $totalHours = collect($tasks)->sum('duration');
            $taskCount = count($tasks);

            $this->info("📊 Summary for $developer:");
            $this->line(" • Total Tasks: $taskCount");
            $this->line(" • Total Hours: $totalHours");
            $this->line(str_repeat('-', 50));
        }

        $weeklyTasks = collect($schedule[1])
            ->flatMap(function($tasks) {
                return collect($tasks)->map(function($task) {
                    return [
                        'week_number' => 1, // Since total_weeks is 1 in your data
                        'task_count' => 1
                    ];
                });
            })
            ->groupBy('week_number')
            ->map(function($tasks) {
                return [
                    'week_number' => $tasks->first()['week_number'],
                    'task_count' => $tasks->count()
                ];
            })
            ->values();

        $this->info("\n📅 Weekly Breakdown:");
        $this->table(
            ['Week', 'Total Tasks'],
            $weeklyTasks->map(fn($item) => [
                $item['week_number'],
                $item['task_count']
            ])->toArray()
        );

        $totalTasks = collect($schedule[1])
            ->flatMap(fn($tasks) => $tasks)
            ->count();
        $totalHours = collect($schedule[1])
            ->flatMap(fn($tasks) => $tasks)
            ->sum('duration');

        $this->info("\n📈 Overall Summary:");
        $this->line(" • Total Developers: " . count($schedule[1]));
        $this->line(" • Total Tasks: $totalTasks");
        $this->line(" • Total Hours: $totalHours");
    }

}
