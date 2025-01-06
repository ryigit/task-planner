<?php

namespace App\Http\Controllers;

use App\Repositories\DeveloperRepository;
use App\Repositories\TaskRepository;
use App\Services\SchedulerService;
use App\Services\TaskProviderService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class ScheduleController extends Controller
{
    private $taskProviderService;
    private $schedulerService;
    private $developerRepository;
    private $taskRepository;

    public function __construct(
        TaskProviderService $taskProviderService,
        SchedulerService $schedulerService,
        DeveloperRepository $developerRepository,
        TaskRepository $taskRepository
    ) {
        $this->taskProviderService = $taskProviderService;
        $this->schedulerService = $schedulerService;
        $this->developerRepository = $developerRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @throws Exception
     */
    public function index(): View|Factory|Application
    {
        $schedule = $this->createSchedule();

        // Return to view with data
        return view('schedule.index', [
            'schedule' => $schedule['schedule'],
            'totalWeeks' => $schedule['total_weeks']
        ]);
    }

    private function createSchedule(): array
    {
        $developerCount = $this->developerRepository->countActiveDevelopers();
        if ($developerCount === 0) {
            throw new Exception('No active developers found');
        }

        $developers = $this->developerRepository->getActiveDevelopers();
        $tasks = $this->taskRepository->getAll();

        return $this->schedulerService->calculateSchedule($developers, $tasks);
    }
}
