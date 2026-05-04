@extends('include.master')
@section('page', 'Daily Tasks')
@section('content')

<div class="main-content right-chat-active">
    <div class="middle-sidebar-bottom">
        <div class="middle-sidebar-left">
            <div class="row">
                <div class="col-xl-8 col-xxl-9 col-lg-8 mx-auto">

                    {{-- Header --}}
                    <div class="card shadow-xss w-100 border-0 p-4 mb-3 rounded-xxl">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="fw-700 mb-1 font-md text-grey-900">
                                    <i class="feather-check-square me-2 text-current"></i> Daily Tasks
                                </h2>
                                <p class="font-xssss text-grey-500 mb-0">Complete tasks to stay active. Refreshes every day!</p>
                            </div>
                            <div class="text-center">
                                <span class="d-block fw-700 font-xl text-current">{{ $completedCount }}/{{ $totalCount }}</span>
                                <span class="font-xsssss text-grey-500 text-uppercase fw-600">Done</span>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mt-3 bg-greylight rounded-pill overflow-hidden" style="height:8px;">
                            <div class="bg-current rounded-pill h-100" style="width:{{ $totalCount > 0 ? ($completedCount / $totalCount) * 100 : 0 }}%;transition:width 0.4s ease;"></div>
                        </div>

                        {{-- XP Display --}}
                        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                            <span class="font-xssss text-grey-500 fw-600">
                                <i class="feather-zap text-warning me-1"></i> Your XP
                            </span>
                            <span class="fw-700 font-xss text-warning">{{ number_format($userXp) }} XP</span>
                        </div>
                    </div>

                    {{-- Task List --}}
                    @foreach($tasks as $task)
                    @php
                        $isAutoComplete = $task->dailyTask->auto_complete_rule !== null;
                        $isMetaTask = $task->dailyTask->auto_complete_rule === 'all_others_done';
                    @endphp
                    <div class="card shadow-xss border-0 p-4 mb-2 rounded-xxl {{ $task->completed ? 'opacity-75' : '' }}" style="{{ $isMetaTask && $task->completed ? 'border: 2px solid #d29922 !important;' : '' }}">
                        <div class="d-flex align-items-center">
                            {{-- Checkbox --}}
                            @if($isMetaTask)
                                {{-- Meta-task: not manually toggleable --}}
                                <div class="me-3" style="width:28px;height:28px;">
                                    @if($task->completed)
                                        <span class="d-flex align-items-center justify-content-center rounded-circle" style="width:28px;height:28px;background:linear-gradient(135deg,#f5a623,#f7c948);">
                                            <i class="feather-star text-white" style="font-size:14px;"></i>
                                        </span>
                                    @else
                                        <span class="d-flex align-items-center justify-content-center bg-greylight rounded-circle" style="width:28px;height:28px;border:2px dashed #ccc;">
                                            <i class="feather-star text-grey-400" style="font-size:12px;"></i>
                                        </span>
                                    @endif
                                </div>
                            @elseif($isAutoComplete && $task->completed)
                                {{-- Auto-completed task: show locked checkmark --}}
                                <div class="me-3" style="width:28px;height:28px;">
                                    <span class="d-flex align-items-center justify-content-center bg-info text-white rounded-circle" style="width:28px;height:28px;">
                                        <i class="feather-check" style="font-size:14px;"></i>
                                    </span>
                                </div>
                            @else
                                {{-- Manual toggle --}}
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="me-3">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent" style="width:28px;height:28px;">
                                        @if($task->completed)
                                            <span class="d-flex align-items-center justify-content-center bg-success text-white rounded-circle" style="width:28px;height:28px;">
                                                <i class="feather-check" style="font-size:14px;"></i>
                                            </span>
                                        @else
                                            <span class="d-flex align-items-center justify-content-center bg-greylight rounded-circle" style="width:28px;height:28px;border:2px solid #ddd;">
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            @endif

                            <div class="flex-grow-1">
                                <h4 class="fw-700 font-xssss text-grey-900 mb-0 {{ $task->completed ? 'text-decoration-line-through' : '' }}">{{ $task->dailyTask->title }}</h4>
                                <p class="font-xsssss text-grey-500 mb-0">{{ $task->dailyTask->description }}</p>
                                @if($isAutoComplete && !$isMetaTask)
                                    <span class="font-xsssss fw-600 {{ $task->completed ? 'text-info' : 'text-grey-400' }}">
                                        <i class="feather-cpu me-1" style="font-size:10px;"></i>Auto-tracked
                                    </span>
                                @endif
                            </div>
                            <div class="text-end ms-3">
                                <span class="badge {{ $task->dailyTask->category === 'gaming' ? 'bg-danger' : ($task->dailyTask->category === 'social' ? 'bg-primary' : ($task->dailyTask->category === 'profile' ? 'bg-warning' : 'bg-secondary')) }} text-white font-xsssss px-2 py-1 rounded-pill mb-1 d-block">{{ ucfirst($task->dailyTask->category) }}</span>
                                <span class="fw-700 font-xsssss text-current">+{{ $task->dailyTask->xp }} XP</span>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($completedCount === $totalCount && $totalCount > 0)
                    <div class="card shadow-xss border-0 p-4 rounded-xxl text-center" style="background: linear-gradient(135deg, #7c3aed, #8b5cf6, #6d28d9);">
                        <h3 class="fw-700 font-xs text-white mb-1">🎉 All Tasks Complete!</h3>
                        <p class="font-xssss text-white mb-0" style="opacity:0.85;">Great job! Come back tomorrow for new tasks.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
