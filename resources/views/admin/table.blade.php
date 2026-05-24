@extends('admin.layout')
@section('title', ucfirst(str_replace('_', ' ', $currentTable)))
@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first('admin') ?: $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@php
    $booleanColumns = ['is_admin', 'completed', 'is_online', 'read'];
    $jsonColumns = ['criteria', 'metadata', 'playstyle_tags', 'options'];
    $longTextColumns = ['content', 'bio', 'description', 'body', 'payload', 'exception', 'user_agent'];
    $dateColumns = ['birthdate', 'assigned_date'];
    $dateTimeColumns = ['read_at', 'earned_at', 'expires_at'];
    $numberColumns = ['user_id', 'badge_id', 'post_id', 'game_id', 'conversation_id', 'sender_id', 'from_user_id', 'daily_task_id', 'audience_list_id', 'xp', 'likes_count', 'comments_count', 'file_size', 'order', 'post_count', 'follower_count', 'following_count', 'attempts', 'reserved_at', 'available_at', 'last_activity', 'expiration', 'cancelled_at', 'finished_at', 'pending_jobs', 'failed_jobs', 'total_jobs'];
@endphp

{{-- Header --}}
<div class="row mb-3">
    <div class="col">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg me-1"></i> New Record
        </button>
    </div>
    <div class="col-auto">
        <span class="badge text-bg-secondary fs-6">{{ $records->total() }} records</span>
    </div>
</div>

{{-- Data Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        @foreach($columns as $col)
                            @if($col === 'password') @continue @endif
                            <th>{{ $col }}</th>
                        @endforeach
                        <th style="width:130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            @foreach($columns as $col)
                                @if($col === 'password') @continue @endif
                                <td title="{{ $record->$col }}" style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ Str::limit($record->$col, 50) }}
                                </td>
                            @endforeach
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#showModal{{ $record->id }}"
                                            title="View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $record->id }}"
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('admin.destroy', [$currentTable, $record->id]) }}" method="POST"
                                          onsubmit="return confirm('Delete this record?')" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{-- Show Modal --}}
                        <div class="modal fade" id="showModal{{ $record->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Record #{{ $record->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <dl class="row mb-0">
                                            @foreach($columns as $col)
                                                @if($col === 'password') @continue @endif
                                                <dt class="col-sm-3 text-muted">{{ ucfirst(str_replace('_', ' ', $col)) }}</dt>
                                                <dd class="col-sm-9 text-break">
                                                    @php($value = $record->$col)
                                                    {{ is_null($value) ? '—' : $value }}
                                                </dd>
                                            @endforeach
                                        </dl>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editModal{{ $record->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.update', [$currentTable, $record->id]) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit #{{ $record->id }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @foreach($editableColumns as $col)
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">{{ ucfirst(str_replace('_', ' ', $col)) }}</label>
                                                    @if(in_array($col, $booleanColumns, true))
                                                        <select name="{{ $col }}" class="form-select">
                                                            <option value="0" {{ !$record->$col ? 'selected' : '' }}>No</option>
                                                            <option value="1" {{ $record->$col ? 'selected' : '' }}>Yes</option>
                                                        </select>
                                                    @elseif($col === 'gender')
                                                        <select name="{{ $col }}" class="form-select">
                                                            <option value="">—</option>
                                                            <option value="male" {{ $record->$col === 'male' ? 'selected' : '' }}>Male</option>
                                                            <option value="female" {{ $record->$col === 'female' ? 'selected' : '' }}>Female</option>
                                                        </select>
                                                    @elseif($col === 'privacy')
                                                        <select name="{{ $col }}" class="form-select">
                                                            <option value="public" {{ $record->$col === 'public' ? 'selected' : '' }}>Public</option>
                                                            <option value="friends" {{ $record->$col === 'friends' ? 'selected' : '' }}>Friends</option>
                                                            <option value="private" {{ $record->$col === 'private' ? 'selected' : '' }}>Private</option>
                                                        </select>
                                                    @elseif($col === 'password')
                                                        <input type="password" name="{{ $col }}" class="form-control" autocomplete="new-password" placeholder="Leave blank to keep current password">
                                                    @elseif(in_array($col, $jsonColumns, true))
                                                        <textarea name="{{ $col }}" class="form-control font-monospace" rows="3">{{ $record->$col }}</textarea>
                                                    @elseif(in_array($col, $longTextColumns, true))
                                                        <textarea name="{{ $col }}" class="form-control" rows="3">{{ $record->$col }}</textarea>
                                                    @elseif(in_array($col, $dateColumns, true))
                                                        <input type="date" name="{{ $col }}" class="form-control" value="{{ $record->$col }}">
                                                    @elseif(in_array($col, $dateTimeColumns, true))
                                                        <input type="datetime-local" name="{{ $col }}" class="form-control" value="{{ $record->$col ? \Carbon\Carbon::parse($record->$col)->format('Y-m-d\TH:i') : '' }}">
                                                    @elseif(in_array($col, $numberColumns, true) || Str::endsWith($col, '_id') || Str::endsWith($col, '_count'))
                                                        <input type="number" name="{{ $col }}" class="form-control" value="{{ $record->$col }}">
                                                    @else
                                                        <input type="text" name="{{ $col }}" class="form-control" value="{{ $record->$col }}">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                No records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($records->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $records->links() }}
</div>
@endif

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.store', $currentTable) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New {{ ucfirst(Str::singular(str_replace('_', ' ', $currentTable))) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @foreach($editableColumns as $col)
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ ucfirst(str_replace('_', ' ', $col)) }}</label>
                            @if(in_array($col, $booleanColumns, true))
                                <select name="{{ $col }}" class="form-select">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            @elseif($col === 'gender')
                                <select name="{{ $col }}" class="form-select">
                                    <option value="">—</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            @elseif($col === 'privacy')
                                <select name="{{ $col }}" class="form-select">
                                    <option value="public">Public</option>
                                    <option value="friends">Friends</option>
                                    <option value="private">Private</option>
                                </select>
                            @elseif(in_array($col, $jsonColumns, true))
                                <textarea name="{{ $col }}" class="form-control font-monospace" rows="3" placeholder='{"key":"value"}'></textarea>
                            @elseif(in_array($col, $longTextColumns, true))
                                <textarea name="{{ $col }}" class="form-control" rows="3"></textarea>
                            @elseif($col === 'password')
                                <input type="password" name="{{ $col }}" class="form-control" autocomplete="new-password">
                            @elseif(in_array($col, $dateColumns, true))
                                <input type="date" name="{{ $col }}" class="form-control">
                            @elseif(in_array($col, $dateTimeColumns, true))
                                <input type="datetime-local" name="{{ $col }}" class="form-control">
                            @elseif(in_array($col, $numberColumns, true) || Str::endsWith($col, '_id') || Str::endsWith($col, '_count'))
                                <input type="number" name="{{ $col }}" class="form-control">
                            @else
                                <input type="text" name="{{ $col }}" class="form-control">
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
