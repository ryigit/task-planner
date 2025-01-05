<?php

namespace App\Services\Provider;

use App\Models\Provider;
use App\Services\Interface\ToDoProviderInterface;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DefaultProvider implements ToDoProviderInterface
{
    private Provider $providerConfig;

    public function __construct($providerConfig)
    {
        $this->providerConfig = $providerConfig;
    }

    public function getTasks(): array
    {
        try {
            $response = Http::get($this->providerConfig->endpoint_url);
            $rawTasks = $response->json();
            $mappings = json_decode($this->providerConfig->field_mappings, true);

            return array_map(function ($rawTask) use ($mappings) {
                return [
                    'name' => $rawTask[$mappings['name']] ?? null,
                    'difficulty' => $rawTask[$mappings['difficulty']] ?? null,
                    'duration' => $rawTask[$mappings['duration']] ?? null,
                    'provider' => $this->providerConfig->name,
                    'original_payload' => json_encode($rawTask)
                ];
            }, $rawTasks);
        } catch (Exception $e) {
            Log::error("Error in DefaultProvider: " . $e->getMessage());
            return [];
        }
    }
}
