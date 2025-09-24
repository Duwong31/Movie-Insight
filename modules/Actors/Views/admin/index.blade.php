@extends('admin.layouts.app')

@section('title', 'Manage Celebrities')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manage Celebrities</h1>
    <a href="{{ route('admin.actors.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New Celebrity
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-sm-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0">Total Celebrities</h5>
                        <h4 class="mb-0">{{ $statistics['total'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-film fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0">With Movies</h5>
                        <h4 class="mb-0">{{ $statistics['with_movies'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h5 class="card-title mb-0">Without Movies</h5>
                        <h4 class="mb-0">{{ $statistics['without_movies'] }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.actors.index') }}" class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="Search by name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('admin.actors.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<div class="card mb-4">
    <div class="card-body">
        <form id="bulkActionForm" method="POST" action="{{ route('admin.actors.bulk-action') }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="bulkAction" class="form-label">Bulk Actions</label>
                    <select name="action" id="bulkAction" class="form-select">
                        <option value="">Select Action</option>
                        <option value="delete">Delete Selected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-warning" onclick="return confirmBulkAction()">
                        <i class="fas fa-cogs"></i> Apply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Actors Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-users"></i> Celebrities List 
            ({{ $actors->total() }} total)
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th width="80">Image</th>
                        <th>Name</th>
                        <th>Nationality</th>
                        <th>Birth Date</th>
                        <th>Movies Count</th>
                        <th width="150" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($actors as $actor)
                        <tr>
                            <td>
                                <input type="checkbox" name="actor_ids[]" value="{{ $actor->actors_id }}" 
                                       class="form-check-input actor-checkbox">
                            </td>
                            <td>
                                <img src="{{ $actor->actors_image ? asset('storage/' . $actor->actors_image) : asset('img/placeholder-actor.jpg') }}" 
                                     alt="{{ $actor->actor_name }}" 
                                     class="img-thumbnail" 
                                     style="width: 50px; height: 70px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $actor->actor_name }}</strong>
                                @if($actor->biography)
                                    <br><small class="text-muted">{{ Str::limit($actor->biography, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $actor->nationality ?? 'N/A' }}</td>
                            <td>
                                @if($actor->birth_date)
                                    {{ \Carbon\Carbon::parse($actor->birth_date)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $actor->movies_count > 0 ? 'success' : 'secondary' }}">
                                    {{ $actor->movies_count }} movies
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.actors.show', $actor) }}" 
                                       class="btn btn-sm btn-outline-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.actors.edit', $actor) }}" 
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.actors.destroy', $actor) }}" method="POST" 
                                          style="display: inline-block;" 
                                          onsubmit="return confirm('Are you sure you want to delete this celebrity?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5>No celebrities found</h5>
                                <p class="text-muted">Start by adding your first celebrity.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($actors->hasPages())
        <div class="card-footer">
            {{ $actors->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const actorCheckboxes = document.querySelectorAll('.actor-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        actorCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update select all when individual checkboxes change
    actorCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.actor-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === actorCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < actorCheckboxes.length;
        });
    });
});

function confirmBulkAction() {
    const selectedCheckboxes = document.querySelectorAll('.actor-checkbox:checked');
    const action = document.getElementById('bulkAction').value;
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one celebrity.');
        return false;
    }
    
    if (!action) {
        alert('Please select an action.');
        return false;
    }
    
    return confirm(`Are you sure you want to ${action} ${selectedCheckboxes.length} selected celebrity/celebrities?`);
}
</script>
@endpush
