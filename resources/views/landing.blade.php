<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FileBox | Secure Cloud Storage</title>
    <meta name="description" content="FileBox is your secure, modern cloud drive for teams and creators. Upload, preview, and share files with real-time quotas and Stripe-powered upgrades.">
    <meta name="keywords" content="cloud storage, file sharing, saas, drive, upload, filebox">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:title" content="FileBox | Secure Cloud Storage">
    <meta property="og:description" content="Upload, preview, and share files. Track storage, upgrade in seconds.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/filebox-logo.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/filebox-logo.svg') }}">
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
            --glow: 0 10px 60px rgba(124,93,255,0.25);
        }
        [data-theme="light"] {
            --bg: #f7f8fb;
            --bg-pattern: radial-gradient(120% 120% at 20% 10%, rgba(124,93,255,0.12), transparent),
                           radial-gradient(80% 80% at 80% 0%, rgba(59,200,246,0.12), transparent),
                           #f7f8fb;
            --surface: #ffffff;
            --surface-alt: #f2f4fb;
            --text: #0f172a;
            --muted: #4b5563;
            --border: #d9deeb;
            --glow: 0 12px 50px rgba(123,97,255,0.20);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Inter", "Segoe UI", system-ui, -apple-system, sans-serif;
            background: var(--bg-pattern);
            color: var(--text);
        }
        a { color: inherit; text-decoration: none; }
        header { display:flex; align-items:center; justify-content:space-between; padding:20px 28px; position:sticky; top:0; backdrop-filter: blur(12px); background: color-mix(in srgb, var(--bg) 85%, transparent); border-bottom:1px solid var(--border); }
        .brand { display:flex; align-items:center; gap:10px; font-weight:700; font-size:18px; }
        .brand-logo { width:36px; height:36px; border-radius:12px; box-shadow: var(--glow); }
        nav { display:flex; gap:14px; align-items:center; font-weight:600; }
        .btn { display:inline-flex; align-items:center; gap:8px; padding:10px 14px; border-radius:10px; border:1px solid var(--border); font-weight:700; color:var(--text); background: linear-gradient(120deg, var(--accent), var(--accent-2)); box-shadow: var(--glow); }
        .btn.secondary { background: transparent; box-shadow:none; }
        .hero { padding:80px 28px 60px; max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1.1fr 0.9fr; gap:32px; align-items:center; }
        .eyebrow { display:inline-flex; align-items:center; gap:8px; padding:6px 10px; border-radius:999px; border:1px solid var(--border); color:var(--muted); font-weight:600; }
        h1 { margin:12px 0 12px; font-size:40px; line-height:1.1; }
        p.lead { margin:0 0 20px; font-size:18px; color:var(--muted); }
        .metrics { display:flex; gap:16px; flex-wrap:wrap; }
        .metric { padding:14px 16px; border:1px solid var(--border); border-radius:14px; background: var(--surface-alt); min-width:150px; }
        .metric strong { font-size:22px; display:block; }
        .panel { border:1px solid var(--border); border-radius:16px; background: var(--surface); padding:18px; box-shadow: var(--glow); }
        .grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px; }
        .feat { border:1px solid var(--border); border-radius:14px; padding:14px; background: var(--surface-alt); }
        .feat h3 { margin:0 0 6px; font-size:16px; }
        footer { padding:30px 28px 40px; color:var(--muted); text-align:center; }
        .theme-toggle { position: fixed; right: 18px; bottom: 18px; width: 48px; height: 48px; border-radius: 50%; border:1px solid var(--border); background: var(--surface); color: var(--text); display:grid; place-items:center; box-shadow: var(--glow); cursor:pointer; }
        @media(max-width:900px){ .hero{grid-template-columns:1fr; padding-top:64px;} nav{gap:10px;} header{position:static;} }
    </style>
</head>
<body>
    <header>
        <div class="brand">
            <img class="brand-logo" src="{{ asset('images/filebox-logo.svg') }}" alt="FileBox" loading="lazy">
            <span>FileBox</span>
        </div>
        <nav>
            <a href="#features">Features</a>
            <a href="#pricing">Pricing</a>
            <a href="#faq">FAQ</a>
            @auth
                <a class="btn secondary" href="{{ route('dashboard') }}">Dashboard</a>
            @else
                <a class="btn secondary" href="{{ route('login') }}">Log in</a>
                <a class="btn" href="{{ route('register') }}">Get started</a>
            @endauth
        </nav>
    </header>

    <section class="hero">
        <div>
            <span class="eyebrow">Secure. Fast. Stripe-ready.</span>
            <h1>Modern cloud drive for your team & clients.</h1>
            <p class="lead">Upload, preview, download, and track storage in real time. Switch plans instantly with Stripe, and keep your data safe with granular access.</p>
            <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:10px;">
                <a class="btn" href="{{ route('register') }}">Start free</a>
                <a class="btn secondary" href="{{ route('login') }}">Log in</a>
            </div>
            <div class="metrics" style="margin-top:18px;">
                <div class="metric"><strong>5 GB</strong><span>Free tier storage</span></div>
                <div class="metric"><strong>100 GB</strong><span>Pro plan storage</span></div>
                <div class="metric"><strong>99.9%</strong><span>Uptime target</span></div>
            </div>
        </div>
        <div class="panel">
            <h3 style="margin:0 0 10px;">Built-in preview & quota tracking</h3>
            <ul style="margin:0; padding-left:18px; color:var(--muted); line-height:1.6;">
                <li>Image previews & PDF inline view</li>
                <li>Per-user storage limits with live usage bar</li>
                <li>Stripe billing ready (Cashier)</li>
                <li>Dark/light auto theme</li>
            </ul>
        </div>
    </section>

    <section id="features" style="max-width:1200px; margin:0 auto; padding:0 28px 40px;">
        <div class="grid">
            <div class="feat">
                <h3>Secure storage</h3>
                <p style="color:var(--muted);">Files stay on the public disk with guarded access; owners only.</p>
            </div>
            <div class="feat">
                <h3>Fast uploads</h3>
                <p style="color:var(--muted);">Drag & drop with live previews before submit.</p>
            </div>
            <div class="feat">
                <h3>Stripe-ready</h3>
                <p style="color:var(--muted);">Cashier installed for instant plan upgrades.</p>
            </div>
            <div class="feat">
                <h3>SEO smart</h3>
                <p style="color:var(--muted);">Meta tags, canonical links, and semantic layout.</p>
            </div>
        </div>
    </section>

    <section id="pricing" style="max-width:1100px; margin:0 auto 40px; padding:0 28px;">
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(260px,1fr));">
            <div class="feat" style="border-color:var(--border);">
                <h3>Free</h3>
                <p style="margin:6px 0;">$0/month</p>
                <p style="color:var(--muted);">5 GB storage, uploads, previews.</p>
                <a class="btn secondary" href="{{ route('register') }}">Start free</a>
            </div>
            <div class="feat" style="border-color:var(--accent); box-shadow: var(--glow);">
                <h3>Pro</h3>
                <p style="margin:6px 0;">$9/month</p>
                <p style="color:var(--muted);">100 GB storage, priority support, advanced sharing.</p>
                <a class="btn" href="{{ route('register') }}">Upgrade</a>
            </div>
        </div>
    </section>

    <section id="faq" style="max-width:900px; margin:0 auto 50px; padding:0 28px;">
        <div class="feat" style="border:1px solid var(--border);">
            <h3>Frequently asked</h3>
            <p style="color:var(--muted); margin-bottom:8px;"><strong>Is my data private?</strong> Yes, files are scoped per user; only owners can access their files.</p>
            <p style="color:var(--muted); margin-bottom:8px;"><strong>Can I upgrade later?</strong> Yes, plans will be managed through Stripe billing.</p>
            <p style="color:var(--muted); margin:0;"><strong>Do you support previews?</strong> Images and PDFs preview inline; other docs show type badges.</p>
        </div>
    </section>

    <footer>
        <div>FileBox — Secure cloud storage SaaS.</div>
        <div style="margin-top:6px;">Built with Laravel 12, Breeze, Cashier.</div>
    </footer>

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
