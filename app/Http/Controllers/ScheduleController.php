<?php

namespace App\Http\Controllers;

use App\Repositories\DeveloperRepository;
use App\Repositories\TaskRepository;
use App\Services\SchedulerService;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

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
        $schedule = $this->createSchedule();

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
