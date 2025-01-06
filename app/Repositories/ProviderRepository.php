<?php

namespace App\Repositories;

use App\Models\Provider;

const TYPE_DEFAULT = 'default';

class ProviderRepository
{
    public function getActiveDefaultProviders(): array
    {
        return Provider::where('is_active', true)
            ->where('type', TYPE_DEFAULT)
            ->get()
            ->all();
    }
}
