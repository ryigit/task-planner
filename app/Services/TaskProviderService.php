<?php

namespace App\Services;

use App\Factory\TodoProviderFactory;
use App\Repositories\ProviderRepository;
use App\Repositories\TaskRepository;
use Illuminate\Support\Facades\Log;

class TaskProviderService
{
    public function __construct(
        private readonly TodoProviderFactory $factory,
        private readonly TaskRepository $taskRepository,
        private readonly ProviderRepository $providerRepository,
    ) {}

    public function fetchAndPersistTasks(): array
    {
        try {
            $dbProviders = $this->providerRepository->getActiveDefaultProviders();

            $allTasks = array_reduce($dbProviders, function (array $tasks, $providerConfig) {
                $provider = $this->factory->create('default', $providerConfig);
                return array_merge($tasks, $provider->getTasks());
            }, []);

            $this->taskRepository->deleteAll();
            $this->taskRepository->saveMany($allTasks);

            Log::info('Tasks synchronized successfully', ['count' => count($allTasks)]);

            return $allTasks;
        } catch (\Exception $e) {
            Log::error('Failed to synchronize tasks', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}

