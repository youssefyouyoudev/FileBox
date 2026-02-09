<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FileBox') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('images/filebox-logo.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/filebox-logo.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                color-scheme: light dark;
                --bg: #0b1020;
                --bg-pattern: radial-gradient(120% 120% at 20% 10%, rgba(124,93,255,0.12), transparent),
                               radial-gradient(80% 80% at 80% 0%, rgba(59,200,246,0.12), transparent),
                               #0b1020;
                --surface: #0f1629;
                --surface-alt: #111a30;
                --text: #e8ecf7;
                --muted: #9aa5bf;
                --accent: #7c5dff;
                --accent-2: #3bc8f6;
                --border: #1c2b4a;
                --shadow: 0 16px 60px rgba(0,0,0,0.45);
                --danger: #f45b69;
                --success: #22c55e;
            }
            [data-theme="light"] {
                --bg: #f7f8fb;
                --bg-pattern: radial-gradient(120% 120% at 20% 10%, rgba(124,93,255,0.12), transparent),
                               radial-gradient(80% 80% at 80% 0%, rgba(59,200,246,0.12), transparent),
                               #f7f8fb;
                --surface: #ffffff;
                --surface-alt: #eef1f7;
                --text: #0f172a;
                --muted: #4b5563;
                --accent: #7c5dff;
                --accent-2: #3bc8f6;
                --border: #d9deeb;
                --shadow: 0 16px 40px rgba(16,24,40,0.12);
                --danger: #d64555;
                --success: #16a34a;
            }
            * { box-sizing: border-box; }
            body {
                margin: 0;
                min-height: 100vh;
                font-family: "Inter", "Segoe UI", system-ui, -apple-system, sans-serif;
                background: var(--bg-pattern);
                color: var(--text);
            }
            a { color: inherit; text-decoration: none; }
            .app-shell { min-height: 100vh; display:flex; flex-direction:column; }
            .topbar {
                position: sticky;
                top: 0;
                z-index: 20;
                display:flex;
                align-items:center;
                justify-content:space-between;
                gap:14px;
                padding:14px 24px;
                background: color-mix(in srgb, var(--bg) 90%, transparent);
                backdrop-filter: blur(12px);
                border-bottom:1px solid var(--border);
            }
            .brand { display:flex; align-items:center; gap:10px; font-weight:700; letter-spacing:0.2px; }
            .brand-logo { width:34px; height:34px; border-radius:12px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); }
            .nav { display:flex; align-items:center; gap:12px; flex-wrap:wrap; }
            .nav a { padding:8px 12px; border-radius:10px; color:var(--muted); font-weight:600; }
            .nav a.active { color:var(--text); background: color-mix(in srgb, var(--surface) 80%, transparent); border:1px solid var(--border); }
            .user-menu { display:flex; align-items:center; gap:10px; color:var(--muted); font-weight:600; }
            .content { flex:1; padding:28px 22px 48px; max-width:1200px; width:100%; margin:0 auto; }
            .page-heading { margin-bottom:16px; }
            .card { background: var(--surface); border:1px solid var(--border); border-radius:16px; box-shadow: var(--shadow); padding:20px; color:var(--text); }
            .card.alt { background: var(--surface-alt); box-shadow:none; }
            .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:10px; border:1px solid transparent; font-weight:700; color:var(--text); background: linear-gradient(120deg, var(--accent), var(--accent-2)); box-shadow: 0 12px 40px rgba(124,93,255,0.28); cursor:pointer; }
            .btn.secondary { background: transparent; color: var(--text); border-color: var(--border); box-shadow:none; }
            .btn.danger { background: transparent; border-color: rgba(244,91,105,0.55); color: var(--text); }
            .btn.small { padding:8px 12px; border-radius:8px; font-size:14px; }
            input, select, textarea {
                background: var(--surface-alt);
                color: var(--text);
                border:1px solid var(--border);
                border-radius:10px;
                padding:10px 12px;
                width:100%;
            }
            label { color: var(--muted); font-weight:600; }
            .theme-toggle {
                position: fixed;
                right: 18px;
                bottom: 18px;
                width: 48px;
                height: 48px;
                border-radius: 50%;
                border:1px solid var(--border);
                background: var(--surface);
                color: var(--text);
                display:grid;
                place-items:center;
                box-shadow: var(--shadow);
                cursor:pointer;
                transition: transform .12s ease, box-shadow .12s ease;
            }
            .theme-toggle:hover { transform: translateY(-1px); box-shadow: 0 12px 40px rgba(0,0,0,0.35); }
        </style>
    </head>
    <body class="antialiased">
        <div class="app-shell">
            <header class="topbar">
                <div class="brand">
                    <img class="brand-logo" src="{{ asset('images/filebox-logo.svg') }}" alt="FileBox" loading="lazy">
                    <a href="{{ route('dashboard') }}">FileBox</a>
                </div>
                <nav class="nav">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('files.index') }}" class="{{ request()->routeIs('files.*') ? 'active' : '' }}">Files</a>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Profile</a>
                </nav>
                <div class="user-menu">
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn small secondary">Log out</button>
                    </form>
                </div>
            </header>

            <main class="content">
                @isset($header)
                    <div class="page-heading">{{ $header }}</div>
                @endisset
                {{ $slot }}
            </main>
        </div>

        <button id="theme-toggle" class="theme-toggle" aria-label="Toggle theme" type="button"></button>
        <script>
            (() => {
                const root = document.documentElement;
                const storageKey = 'filebox-theme';
                const saved = localStorage.getItem(storageKey);
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const initial = saved || (prefersDark ? 'dark' : 'light');
                setTheme(initial);

                function setTheme(theme) {
                    root.setAttribute('data-theme', theme);
                    localStorage.setItem(storageKey, theme);
                    updateIcon(theme);
                }

                function updateIcon(theme) {
                    const btn = document.getElementById('theme-toggle');
                    if (!btn) return;
                    btn.textContent = theme === 'dark' ? '☾' : '☀';
                }

                document.getElementById('theme-toggle')?.addEventListener('click', () => {
                    const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    setTheme(next);
                });
            })();
        </script>
    </body>
</html>
