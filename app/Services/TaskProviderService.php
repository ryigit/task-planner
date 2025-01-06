<?php

namespace App\Services;

use App\Factory\TodoProviderFactory;
use App\Repositories\TaskRepository;
use App\Models\Provider;

class TaskProviderService
{
    public function __construct(
        private readonly TodoProviderFactory $factory,
        private readonly TaskRepository $taskRepository
    ) {}

    public function fetchAndPersistTasks(): array
    {
        $allTasks = [];

        // Fetch active providers from the database
        $dbProviders = Provider::where('is_active', true)
            ->where('type', 'default')
            ->get();

        foreach ($dbProviders as $providerConfig) {
            $provider = $this->factory->create('default', $providerConfig);
            $tasks = $provider->getTasks();
            $allTasks = array_merge($allTasks, $tasks);
        }

        // Persist tasks using the repository
        $this->taskRepository->deleteAll();
        $this->taskRepository->saveMany($allTasks);

        return $allTasks;
    }
}

