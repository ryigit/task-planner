<!-- resources/views/providers/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Providers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Providers</h1>
        <a href="{{ route('providers.create') }}" class="btn btn-primary">Add New Provider</a>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Endpoint URL</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($providers as $provider)
        <tr>
            <td>{{ $provider->name }}</td>
            <td>{{ $provider->type }}</td>
            <td>{{ $provider->endpoint_url }}</td>
            <td>
                            <span class="badge bg-{{ $provider->is_active ? 'success' : 'danger' }}">
                                {{ $provider->is_active ? 'Active' : 'Inactive' }}
                            </span>
            </td>
            <td>
                <a href="{{ route('providers.edit', $provider) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('providers.destroy', $provider) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
