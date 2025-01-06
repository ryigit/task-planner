<?php

namespace App\Services;

use App\Factory\TodoProviderFactory;
use App\Models\Provider;
use App\Models\Task;

class TaskProviderService
{
    private TodoProviderFactory $factory;

    public function __construct(TodoProviderFactory $factory)
    {
        $this->factory = $factory;
    }

    public function fetchAllTasks(): array
    {
        $allTasks = [];

        // Handle mappable providers from database
        $dbProviders = Provider::where('is_active', true)
            ->where('type', 'default')
            ->get();

        foreach ($dbProviders as $providerConfig) {
            $provider = $this->factory->create('default', $providerConfig);
            $tasks = $provider->getTasks();
            $allTasks = array_merge($allTasks, $tasks);
        }

        foreach ($allTasks as $task) {
            Task::create([
                'name' => $task['name'],
                'complexity' => $task['complexity'],
                'duration' => $task['duration'],
                'provider_id' => $task['provider_id'],
                'source_id' => $task['source_id'],
                'original_payload' => $task['original_payload']
            ]);
        }

        return $allTasks;
    }
}
