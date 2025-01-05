<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $provider->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Edit {{ $provider->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('providers.update', $provider->id) }}" method="POST" class="mt-4">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="name" class="form-label">Provider Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $provider->name }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="default" {{ $provider->type == 'default' ? 'selected' : '' }}>Default</option>
                <option value="custom" {{ $provider->type == 'custom' ? 'selected' : '' }}>Custom</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="endpoint_url" class="form-label">Endpoint URL</label>
            <input type="url" class="form-control" id="endpoint_url" name="endpoint_url" value="{{ $provider->endpoint_url }}" required>
        </div>

        <div class="mb-3" id="mappingsDiv">
            <label for="field_mappings" class="form-label">Field Mappings (JSON)</label>
            <textarea class="form-control" id="field_mappings" name="field_mappings" rows="5">{{ $provider->field_mappings }}</textarea>
            <small class="form-text text-muted">Enter JSON mapping of provider fields to system fields</small>
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ $provider->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Provider</button>
        <a href="{{ route('providers.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    document.getElementById('type').addEventListener('change', function() {
        const mappingsDiv = document.getElementById('mappingsDiv');
        mappingsDiv.style.display = this.value === 'default' ? 'block' : 'none';
    });
</script>
</body>
</html>
