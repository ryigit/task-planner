@props(['title', 'value', 'icon'])

<div class="col-md-4">
    <div class="card bg-light h-100">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="bi {{ $icon }} h3 mb-0"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="text-muted mb-0">{{ $title }}</h6>
                    <div class="h3 mb-0">{{ $value }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
