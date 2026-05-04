<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin — @yield('title', 'Dashboard') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />

    {{-- OverlayScrollbars --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />

    {{-- AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}" />

    <style>
        /* ── Modern Dark Override ── */
        :root {
            --admin-bg: #0f1117;
            --admin-surface: #1a1d27;
            --admin-surface-2: #222636;
            --admin-border: #2a2e3d;
            --admin-accent: #6366f1;
            --admin-accent-hover: #818cf8;
            --admin-text: #e2e8f0;
            --admin-text-muted: #64748b;
            --admin-danger: #ef4444;
            --admin-success: #22c55e;
            --admin-warning: #f59e0b;
        }
        body { background: var(--admin-bg) !important; color: var(--admin-text); }

        /* Header */
        .app-header { background: var(--admin-surface) !important; border-bottom: 1px solid var(--admin-border); }

        /* Sidebar */
        .app-sidebar { background: var(--admin-surface) !important; border-right: 1px solid var(--admin-border); }
        .sidebar-brand { border-bottom: 1px solid var(--admin-border); }
        .sidebar-menu .nav-link.active { background: rgba(99,102,241,.15) !important; color: var(--admin-accent) !important; border-radius: 8px; }
        .sidebar-menu .nav-link:hover { background: var(--admin-surface-2) !important; border-radius: 8px; }
        .sidebar-menu .nav-header { color: var(--admin-text-muted); font-size: .65rem; letter-spacing: 1.5px; }

        /* Main content */
        .app-main { background: var(--admin-bg); }
        .app-content-header { border-bottom: 1px solid var(--admin-border); }

        /* Cards */
        .card { background: var(--admin-surface) !important; border: 1px solid var(--admin-border) !important; border-radius: 12px !important; }
        .card-header { background: var(--admin-surface-2) !important; border-bottom: 1px solid var(--admin-border) !important; border-radius: 12px 12px 0 0 !important; }

        /* Tables */
        .table { color: var(--admin-text) !important; --bs-table-bg: transparent; --bs-table-striped-bg: rgba(255,255,255,.02); --bs-table-hover-bg: rgba(99,102,241,.06); }
        .table thead th { background: var(--admin-surface-2) !important; color: var(--admin-text-muted) !important; font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--admin-border) !important; }
        .table td { border-bottom: 1px solid var(--admin-border) !important; vertical-align: middle; }

        /* Buttons */
        .btn-primary { background: var(--admin-accent) !important; border-color: var(--admin-accent) !important; }
        .btn-primary:hover { background: var(--admin-accent-hover) !important; border-color: var(--admin-accent-hover) !important; }
        .btn-outline-primary { color: var(--admin-accent) !important; border-color: var(--admin-accent) !important; }
        .btn-outline-primary:hover { background: var(--admin-accent) !important; color: #fff !important; }
        .btn-outline-danger { color: var(--admin-danger) !important; border-color: var(--admin-danger) !important; }
        .btn-outline-danger:hover { background: var(--admin-danger) !important; color: #fff !important; }

        /* Modals */
        .modal-content { background: var(--admin-surface) !important; border: 1px solid var(--admin-border) !important; border-radius: 16px !important; }
        .modal-header { border-bottom: 1px solid var(--admin-border) !important; }
        .modal-footer { border-top: 1px solid var(--admin-border) !important; }
        .form-control, .form-select { background: var(--admin-bg) !important; border: 1px solid var(--admin-border) !important; color: var(--admin-text) !important; border-radius: 8px !important; }
        .form-control:focus, .form-select:focus { border-color: var(--admin-accent) !important; box-shadow: 0 0 0 3px rgba(99,102,241,.15) !important; }
        .form-label { color: var(--admin-text-muted) !important; font-size: .8rem; }

        /* Pagination */
        .pagination { gap: 4px; }
        .page-link { background: var(--admin-surface) !important; border: 1px solid var(--admin-border) !important; color: var(--admin-text-muted) !important; border-radius: 8px !important; padding: .4rem .75rem; font-size: .85rem; transition: all .15s; }
        .page-link:hover { background: var(--admin-surface-2) !important; color: var(--admin-text) !important; }
        .page-item.active .page-link { background: var(--admin-accent) !important; border-color: var(--admin-accent) !important; color: #fff !important; }
        .page-item.disabled .page-link { background: var(--admin-bg) !important; color: var(--admin-border) !important; }

        /* Small boxes (dashboard) */
        .small-box { border-radius: 12px !important; border: 1px solid var(--admin-border); }
        .small-box .inner h3 { font-size: 2rem; }
        .small-box-footer { border-radius: 0 0 12px 12px; }
        .text-bg-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6) !important; }
        .text-bg-success { background: linear-gradient(135deg, #22c55e, #16a34a) !important; }
        .text-bg-warning { background: linear-gradient(135deg, #f59e0b, #d97706) !important; }
        .text-bg-danger { background: linear-gradient(135deg, #ef4444, #dc2626) !important; }

        /* Badges */
        .badge.text-bg-secondary { background: var(--admin-surface-2) !important; color: var(--admin-text-muted); }
        .badge.text-bg-warning { background: rgba(245,158,11,.15) !important; color: var(--admin-warning); }

        /* Alerts */
        .alert-success { background: rgba(34,197,94,.1) !important; border-color: rgba(34,197,94,.2) !important; color: var(--admin-success) !important; }

        /* Footer */
        .app-footer { background: var(--admin-surface) !important; border-top: 1px solid var(--admin-border) !important; color: var(--admin-text-muted); }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--admin-bg); }
        ::-webkit-scrollbar-thumb { background: var(--admin-border); border-radius: 3px; }

        /* btn-close */
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    </style>

    @stack('styles')
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        {{-- ═══ HEADER ═══ --}}
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="bi bi-arrow-left me-1"></i> Back to Site
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display:none"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            @auth
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            @endauth
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @auth
                            <li><a class="dropdown-item" href="{{ route('user.profile', auth()->user()->username) }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign out</button>
                                </form>
                            </li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        {{-- ═══ SIDEBAR ═══ --}}
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ route('admin.index') }}" class="brand-link">
                    <i class="bi bi-shield-fill-check text-primary me-2"></i>
                    <span class="brand-text fw-light">Nexus Admin</span>
                </a>
            </div>

            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">

                        <li class="nav-item">
                            <a href="{{ route('admin.index') }}" class="nav-link {{ !isset($currentTable) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <li class="nav-header">DATABASE TABLES</li>

                        @foreach($tables as $t)
                        <li class="nav-item">
                            <a href="{{ route('admin.table', $t) }}" class="nav-link {{ (isset($currentTable) && $currentTable === $t) ? 'active' : '' }}">
                                <i class="nav-icon bi bi-table"></i>
                                <p>{{ ucfirst(str_replace('_', ' ', $t)) }}</p>
                            </a>
                        </li>
                        @endforeach

                        <li class="nav-header">NAVIGATION</li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i class="nav-icon bi bi-arrow-left-circle"></i>
                                <p>Back to Site</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        {{-- ═══ MAIN CONTENT ═══ --}}
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">@yield('title', 'Dashboard')</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Admin</a></li>
                                <li class="breadcrumb-item active">@yield('title', 'Dashboard')</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        {{-- ═══ FOOTER ═══ --}}
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">AdminLTE v4</div>
            <strong>&copy; {{ date('Y') }} <a href="{{ route('dashboard') }}">Nexus</a>.</strong> All rights reserved.
        </footer>

    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = { scrollbarTheme: 'os-theme-light', scrollbarAutoHide: 'leave', scrollbarClickScroll: true };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: { theme: Default.scrollbarTheme, autoHide: Default.scrollbarAutoHide, clickScroll: Default.scrollbarClickScroll },
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
