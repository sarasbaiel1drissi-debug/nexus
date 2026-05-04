@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')

{{-- Stats Cards --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3>{{ $stats['users'] }}</h3>
                <p>Users</p>
            </div>
            <i class="small-box-icon bi bi-people-fill"></i>
            <a href="{{ route('admin.table', 'users') }}" class="small-box-footer">
                View All <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3>{{ $stats['posts'] }}</h3>
                <p>Posts</p>
            </div>
            <i class="small-box-icon bi bi-file-earmark-text-fill"></i>
            <a href="{{ route('admin.table', 'posts') }}" class="small-box-footer">
                View All <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3>{{ $stats['badges'] }}</h3>
                <p>Badges</p>
            </div>
            <i class="small-box-icon bi bi-award-fill"></i>
            <a href="{{ route('admin.table', 'badges') }}" class="small-box-footer">
                View All <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3>{{ $stats['daily_tasks'] }}</h3>
                <p>Daily Tasks</p>
            </div>
            <i class="small-box-icon bi bi-check2-square"></i>
            <a href="{{ route('admin.table', 'daily_tasks') }}" class="small-box-footer">
                View All <i class="bi bi-arrow-right-short"></i>
            </a>
        </div>
    </div>
</div>

{{-- Recent Activity --}}
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-people me-2"></i>Recent Users</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>XP</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td><span class="text-muted">@</span>{{ $user->username }}</td>
                            <td><span class="badge text-bg-warning">{{ $user->xp }} XP</span></td>
                            <td>{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-table me-2"></i>Database Tables</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Table</th>
                            <th>Records</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tableCounts as $tableName => $count)
                        <tr>
                            <td><i class="bi bi-table me-1 text-muted"></i> {{ str_replace('_', ' ', $tableName) }}</td>
                            <td><span class="badge text-bg-secondary">{{ number_format($count) }}</span></td>
                            <td><a href="{{ route('admin.table', $tableName) }}" class="btn btn-sm btn-outline-primary">Manage</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
