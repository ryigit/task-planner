<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DeveloperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $developers = [
            [
                'name' => 'DEV1',
                'efficiency_rate' => 1,
                'weekly_hours' => 45,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DEV2',
                'efficiency_rate' => 2,
                'weekly_hours' => 45,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DEV3',
                'efficiency_rate' => 3,
                'weekly_hours' => 45,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DEV4',
                'efficiency_rate' => 4,
                'weekly_hours' => 45,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DEV5',
                'efficiency_rate' => 5,
                'weekly_hours' => 45,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('developers')->insert($developers);
    }
}
