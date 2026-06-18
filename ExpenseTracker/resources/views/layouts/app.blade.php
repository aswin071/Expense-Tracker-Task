<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ExpenseTracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
            min-height: 100vh;
        }
        .app-shell {
            max-width: 430px;
            margin: 0 auto;
            background: #f0f2f5;
            min-height: 100vh;
            position: relative;
        }
        /* Top header */
        .app-header {
            background: #fff;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8eaed;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .app-header .brand {
            font-size: 18px;
            font-weight: 700;
            color: #4f46e5;
            letter-spacing: -0.3px;
        }
        .app-header .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #4f46e5;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        /* Flash messages */
        .flash-success {
            background: #dcfce7;
            border-left: 4px solid #16a34a;
            color: #15803d;
            padding: 12px 16px;
            margin: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
        }
        .flash-error {
            background: #fee2e2;
            border-left: 4px solid #dc2626;
            color: #b91c1c;
            padding: 12px 16px;
            margin: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
        }
        /* Main content */
        .page-content {
            padding: 16px 16px 90px;
        }
        /* Bottom nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: #fff;
            border-top: 1px solid #e8eaed;
            display: flex;
            z-index: 200;
            padding-bottom: env(safe-area-inset-bottom, 0px);
        }
        .bottom-nav a, .bottom-nav button {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 4px 8px;
            text-decoration: none;
            font-size: 10px;
            color: #9ca3af;
            background: none;
            border: none;
            cursor: pointer;
            gap: 4px;
            font-family: inherit;
            transition: color 0.15s;
        }
        .bottom-nav a.active, .bottom-nav a:hover {
            color: #4f46e5;
        }
        .bottom-nav .nav-icon {
            font-size: 20px;
            line-height: 1;
        }
        .bottom-nav .nav-label { font-size: 10px; font-weight: 500; }
        /* FAB-style add button */
        .bottom-nav .nav-add {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 4px 8px;
            text-decoration: none;
            font-size: 10px;
            color: #fff;
            gap: 4px;
        }
        .bottom-nav .nav-add .nav-icon-wrap {
            width: 44px;
            height: 44px;
            background: #4f46e5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-top: -18px;
            box-shadow: 0 4px 12px rgba(79,70,229,0.45);
        }
        .bottom-nav .nav-add .nav-label { color: #4f46e5; font-size: 10px; font-weight: 500; }
        /* Cards */
        .card {
            background: #fff;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .card-title {
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        /* Section heading */
        .section-heading {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 12px;
            margin-top: 4px;
        }
        /* Form styles */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            color: #1a1a2e;
            background: #fff;
            outline: none;
            font-family: inherit;
            transition: border-color 0.15s;
            appearance: none;
            -webkit-appearance: none;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #4f46e5;
        }
        .form-error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        /* Buttons */
        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-family: inherit;
            transition: background 0.15s;
        }
        .btn-primary:hover { background: #4338ca; }
        .btn-secondary {
            display: block;
            width: 100%;
            padding: 13px;
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            font-family: inherit;
        }
        .btn-danger {
            display: inline-block;
            padding: 7px 14px;
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
        }
        .btn-ghost {
            display: inline-block;
            padding: 7px 14px;
            background: #f3f4f6;
            color: #374151;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-family: inherit;
        }
        /* Category badge */
        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-food       { background: #fef3c7; color: #92400e; }
        .badge-transportation { background: #dbeafe; color: #1e40af; }
        .badge-entertainment { background: #ede9fe; color: #5b21b6; }
        .badge-health     { background: #dcfce7; color: #166534; }
        .badge-shopping   { background: #fce7f3; color: #9d174d; }
        .badge-utilities  { background: #e0f2fe; color: #0c4a6e; }
        .badge-other      { background: #f3f4f6; color: #374151; }
        /* Status badges */
        .badge-paid       { background: #dcfce7; color: #15803d; }
        .badge-overdue    { background: #fee2e2; color: #b91c1c; }
        .badge-upcoming   { background: #f3f4f6; color: #6b7280; }
        .badge-active     { background: #dbeafe; color: #1e40af; }
        .badge-paused     { background: #f3f4f6; color: #9ca3af; }
        /* Expense list item */
        .expense-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
        }
        .expense-item:last-child { border-bottom: none; }
        .expense-item-left { display: flex; align-items: center; gap: 12px; }
        .expense-cat-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .expense-item-desc { font-size: 14px; font-weight: 600; color: #1a1a2e; }
        .expense-item-meta { font-size: 12px; color: #9ca3af; margin-top: 2px; }
        .expense-item-amount { font-size: 15px; font-weight: 700; color: #1a1a2e; }
        /* Progress bar */
        .progress-bar-wrap {
            background: #f3f4f6;
            border-radius: 6px;
            height: 6px;
            overflow: hidden;
            margin-top: 6px;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width 0.3s;
        }
        /* Divider */
        .divider { height: 1px; background: #f3f4f6; margin: 12px 0; }
        /* Page header row */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .page-header h1 {
            font-size: 20px;
            font-weight: 700;
        }
        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #4f46e5;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 14px;
        }
    </style>
</head>
<body>
<div class="app-shell">

    <header class="app-header">
        <span class="brand">ExpenseTracker</span>
        <a href="{{ route('profile.edit') }}" class="user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </a>
    </header>

    @if (session('success'))
        <div class="flash-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="flash-error">{{ session('error') }}</div>
    @endif

    <main class="page-content">
        {{ $slot }}
    </main>

    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9.5L12 3l9 6.5V20a1 1 0 01-1 1H4a1 1 0 01-1-1V9.5z"/><path d="M9 21V12h6v9"/></svg>
            <span class="nav-label">Home</span>
        </a>
        <a href="{{ route('expenses.index') }}" class="{{ request()->routeIs('expenses.*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
            <span class="nav-label">Expenses</span>
        </a>
        <a href="{{ route('expenses.create') }}" class="nav-add">
            <span class="nav-icon-wrap">
                <svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            </span>
            <span class="nav-label">Add</span>
        </a>
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="12" width="4" height="9" rx="1"/><rect x="10" y="7" width="4" height="14" rx="1"/><rect x="17" y="3" width="4" height="18" rx="1"/></svg>
            <span class="nav-label">Reports</span>
        </a>
        <a href="{{ route('coach.index') }}" class="{{ request()->routeIs('coach.*') ? 'active' : '' }}">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2"/></svg>
            <span class="nav-label">Coach</span>
        </a>
    </nav>

</div>
</body>
</html>
