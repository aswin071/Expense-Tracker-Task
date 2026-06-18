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
            background: linear-gradient(145deg, #4f46e5 0%, #7c3aed 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .guest-shell {
            width: 100%;
            max-width: 390px;
        }
        .guest-logo {
            text-align: center;
            margin-bottom: 28px;
        }
        .guest-logo .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 18px;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .guest-logo h1 {
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .guest-logo p {
            color: rgba(255,255,255,0.75);
            font-size: 13px;
            margin-top: 4px;
        }
        .guest-card {
            background: #fff;
            border-radius: 20px;
            padding: 28px 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .guest-card h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 20px;
        }
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .form-input {
            width: 100%;
            padding: 13px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            color: #1a1a2e;
            outline: none;
            font-family: inherit;
            transition: border-color 0.15s;
        }
        .form-input:focus { border-color: #4f46e5; }
        .form-error { color: #dc2626; font-size: 12px; margin-top: 4px; }
        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .form-row label { display: flex; align-items: center; gap: 6px; color: #374151; }
        .form-row a { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .btn-primary {
            display: block;
            width: 100%;
            padding: 14px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            text-align: center;
            font-family: inherit;
            letter-spacing: 0.2px;
        }
        .guest-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6b7280;
        }
        .guest-footer a { color: #4f46e5; font-weight: 600; text-decoration: none; }
        .flash-success {
            background: #dcfce7;
            border-left: 4px solid #16a34a;
            color: #15803d;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
<div class="guest-shell">
    <div class="guest-logo">
        <div class="logo-icon">&#128179;</div>
        <h1>ExpenseTracker</h1>
        <p>Track every rupee, every day</p>
    </div>
    <div class="guest-card">
        {{ $slot }}
    </div>
</div>
</body>
</html>
