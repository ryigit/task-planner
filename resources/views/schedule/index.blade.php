<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="mb-0">
                <i class="bi bi-calendar-check"></i> Task Schedule
            </h1>
            <p class="text-muted mb-0">
                Total Weeks: {{ $totalWeeks }}
            </p>
        </div>
    </div>

    @php
        $totalTasks = collect($schedule[1])->flatMap(fn($tasks) => $tasks)->count();
        $totalHours = collect($schedule[1])->flatMap(fn($tasks) => $tasks)->sum('duration');
        $activeDevelopers = collect($schedule[1])->filter(fn($tasks) => count($tasks) > 0)->count();
    @endphp

    <div class="row g-4 mb-4">
        <x-stats-card
            title="Active Developers"
            :value="$activeDevelopers"
            icon="bi-people-fill"
        />
        <x-stats-card
            title="Total Tasks"
            :value="$totalTasks"
            icon="bi-list-check"
        />
        <x-stats-card
            title="Total Hours"
            :value="$totalHours"
            icon="bi-clock-history"
        />
    </div>

    <div class="row mb-4">
        <div class="col">
            @foreach ($schedule[1] as $developer => $tasks)
                <x-developer-card
                    :developer="$developer"
                    :tasks="$tasks"
                />
            @endforeach
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">
                <i class="bi bi-graph-up"></i> Weekly Overview
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>Week</th>
                        <th>Total Tasks</th>
                        <th>Progress</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($schedule as $weekNumber => $developers)
                        @php
                            $weekTasks = collect($developers)->flatMap(fn($tasks) => $tasks)->count();
                            $percentage = $totalTasks > 0 ? ($weekTasks / $totalTasks) * 100 : 0;
                        @endphp
                        <tr>
                            <td>Week {{ $weekNumber }}</td>
                            <td>{{ $weekTasks }}</td>
                            <td>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{ $percentage }}%"
                                         aria-valuenow="{{ $percentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ number_format($percentage, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
