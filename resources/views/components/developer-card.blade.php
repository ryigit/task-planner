@props(['developer', 'tasks'])

<div class="card my-3">
    <div class="card-header bg-light">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <i class="bi bi-person-badge"></i> {{ $developer }}
            </h3>
            <span class="badge bg-primary">
                {{ count($tasks) }} Tasks
            </span>
        </div>
    </div>
    <div class="card-body">
        @if (empty($tasks))
            <div class="text-muted">
                <i class="bi bi-inbox"></i> No tasks assigned
            </div>
        @else
            <div class="row g-3">
                @foreach ($tasks as $task)
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title">Task #{{ $task['id'] }}</h5>
                                    <span class="badge {{ $task['complexity'] === 'High' ? 'bg-danger' : ($task['complexity'] === 'Medium' ? 'bg-warning' : 'bg-success') }}">
                                        {{ $task['complexity'] ?? 'Not specified' }}
                                    </span>
                                </div>
                                <p class="card-text">
                                    <i class="bi bi-clock"></i> {{ $task['duration'] }} hours
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-3 border-top pt-3">
                @php
                    $totalHours = collect($tasks)->sum('duration');
                    $taskCount = count($tasks);
                @endphp
                <div class="row text-center">
                    <div class="col-6">
                        <div class="h4 mb-0">{{ $taskCount }}</div>
                        <small class="text-muted">Total Tasks</small>
                    </div>
                    <div class="col-6">
                        <div class="h4 mb-0">{{ $totalHours }}</div>
                        <small class="text-muted">Total Hours</small>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
