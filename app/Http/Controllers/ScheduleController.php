<?php

namespace App\Http\Controllers;

use App\Repositories\DeveloperRepository;
use App\Repositories\TaskRepository;
use App\Services\SchedulerService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function __construct(
        private readonly SchedulerService    $schedulerService,
        private readonly DeveloperRepository $developerRepository,
        private readonly TaskRepository      $taskRepository
    ) {
    }

    /**
     * @throws Exception
     */
    public function index(): View|Factory|Application
    {
        try {
            // Check for developers first
            $developerCount = $this->developerRepository->countActiveDevelopers();
            if ($developerCount === 0) {
                return view('schedule.index', [
                    'noDevelopers' => true
                ]);
            }

            // Check for tasks
            $tasks = $this->taskRepository->getAll();
            if (count($tasks) === 0) {
                return view('schedule.index', [
                    'noTasks' => true
                ]);
            }

            // If we have both, create schedule
            $developers = $this->developerRepository->getActiveDevelopers();
            $schedule = $this->schedulerService->calculateSchedule($developers, $tasks);

            return view('schedule.index', [
                'schedule' => $schedule['schedule'],
                'totalWeeks' => $schedule['total_weeks']
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create schedule', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('schedule.index', [
                'error' => 'An error occurred while creating the schedule. Please try again later.'
            ]);
        }
    }

    /**
     * @throws Exception
     */
    private function createSchedule(): ?array
    {
        $developerCount = $this->developerRepository->countActiveDevelopers();

        if ($developerCount === 0) {
            return null;
        }

        try {
            $developers = $this->developerRepository->getActiveDevelopers();
            $tasks = $this->taskRepository->getAll();

            Log::info('Creating schedule', [
                'developer_count' => count($developers),
                'task_count' => count($tasks)
            ]);

            return $this->schedulerService->calculateSchedule($developers, $tasks);
        } catch (Exception $e) {
            Log::error('Error while fetching data for schedule', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }
}
