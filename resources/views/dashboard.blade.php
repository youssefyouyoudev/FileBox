<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0; font-size:22px; font-weight:800; color:var(--text);">Dashboard</h2>
                <span style="color:var(--muted);">Your storage, activity, and billing at a glance.</span>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                <form method="POST" action="{{ route('billing.checkout') }}">
                    @csrf
                    <button class="btn" type="submit">Upgrade with Stripe</button>
                </form>
                <form method="POST" action="{{ route('billing.portal') }}">
                    @csrf
                    <input type="hidden" name="return_url" value="{{ route('dashboard') }}">
                    <button class="btn secondary" type="submit">Manage billing</button>
                </form>
            </div>
        </div>
    </x-slot>

    @php
        $usedMb = number_format(($stats['used'] ?? 0) / 1048576, 2);
        $limitMb = number_format(($stats['limit'] ?? 0) / 1048576, 2);
        $percent = ($stats['limit'] ?? 0) > 0 ? round(($stats['used'] / $stats['limit']) * 100, 1) : 0;
        $avgSizeRaw = $stats['avg_size'] ?? 0;
        $avgSize = $avgSizeRaw >= 1048576 ? number_format($avgSizeRaw / 1048576, 2) . ' MB' : number_format($avgSizeRaw / 1024, 1) . ' KB';
        $lastUpload = $stats['last_upload'] ? $stats['last_upload']->format('M d, Y H:i') : '—';
        $profileScore = ($user->email_verified_at ? 50 : 20) + ($user->stripe_customer_id ?? false ? 20 : 0) + 30; // rough heuristic
        $profileScore = min(100, $profileScore);
    @endphp

    <style>
        .dash-grid { display:grid; gap:12px; grid-template-columns: repeat(auto-fit,minmax(240px,1fr)); }
        .mini-bar { width:100%; height:10px; border-radius:999px; background:var(--surface-alt); border:1px solid var(--border); overflow:hidden; }
        .mini-bar-fill { height:100%; background:linear-gradient(120deg,var(--accent),var(--accent-2)); }
        .list-item { display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--border); gap:12px; }
        .list-item:last-child { border-bottom:none; }
        .chip { padding:6px 10px; border-radius:999px; border:1px solid var(--border); background:var(--surface-alt); color:var(--text); font-weight:700; font-size:12px; }
        .pill-soft { padding:6px 10px; border-radius:10px; background:color-mix(in srgb, var(--surface-alt) 70%, transparent); color:var(--muted); font-weight:700; }
    </style>

    <div style="display:grid; gap:16px;">
        <div class="card" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:14px; align-items:center;">
            <div>
                <div style="font-size:18px; font-weight:800;">Upgrade to Pro</div>
                <div style="color:var(--muted); margin-top:4px;">Unlock higher storage, priority uploads, and billing powered by Stripe.</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:10px;">
                    <form method="POST" action="{{ route('billing.checkout') }}">
                        @csrf
                        <button class="btn" type="submit">Upgrade with Stripe</button>
                    </form>
                    <form method="POST" action="{{ route('billing.portal') }}">
                        @csrf
                        <input type="hidden" name="return_url" value="{{ route('dashboard') }}">
                        <button class="btn secondary" type="submit">Manage billing</button>
                    </form>
                </div>
            </div>
            <div class="card alt" style="box-shadow:none;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-weight:700;">Storage</div>
                        <div style="color:var(--muted);">{{ $usedMb }} / {{ $limitMb }} MB</div>
                    </div>
                    <span class="chip">{{ $percent }}%</span>
                </div>
                <div class="usage-bar" style="margin-top:8px; height:12px;">
                    <div class="usage-fill" style="width: {{ min(100, $percent) }}%;"></div>
                </div>
                <div style="display:flex; gap:12px; margin-top:10px; flex-wrap:wrap;">
                    <span class="pill-soft">Files: {{ $stats['files'] ?? 0 }}</span>
                    <span class="pill-soft">Folders: {{ $stats['folders'] ?? 0 }}</span>
                    <span class="pill-soft">Avg size: {{ $avgSize }}</span>
                    <span class="pill-soft">Last upload: {{ $lastUpload }}</span>
                </div>
            </div>
        </div>

        <div class="dash-grid">
            <div class="card alt">
                <div style="font-weight:800;">Uploads (last 7 days)</div>
                <div style="font-size:30px; font-weight:800; margin:4px 0;">{{ $stats['uploads_last_7'] ?? 0 }}</div>
                <div style="color:var(--muted);">Recent activity volume</div>
                <div class="mini-bar" style="margin-top:8px;"><div class="mini-bar-fill" style="width: {{ min(100, ($stats['uploads_last_7'] ?? 0) * 10) }}%;"></div></div>
            </div>
            <div class="card alt">
                <div style="font-weight:800;">Files</div>
                <div style="font-size:30px; font-weight:800; margin:4px 0;">{{ $stats['files'] ?? 0 }}</div>
                <div style="color:var(--muted);">Stored items</div>
            </div>
            <div class="card alt">
                <div style="font-weight:800;">Folders</div>
                <div style="font-size:30px; font-weight:800; margin:4px 0;">{{ $stats['folders'] ?? 0 }}</div>
                <div style="color:var(--muted);">Organized spaces</div>
            </div>
            <div class="card alt">
                <div style="font-weight:800;">Profile</div>
                <div class="mini-bar" style="margin:8px 0; height:10px;"><div class="mini-bar-fill" style="width: {{ $profileScore }}%;"></div></div>
                <div style="color:var(--muted);">{{ $profileScore }}% complete • <a href="{{ route('profile.edit') }}" class="btn small secondary" style="padding:6px 10px;">Open profile</a></div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:2fr 1fr; gap:12px; align-items:start;">
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <div style="font-weight:800;">Recent activity</div>
                    <span class="pill-soft">Last 5 uploads</span>
                </div>
                @forelse ($recent as $item)
                    <div class="list-item">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="chip" style="border-radius:10px; text-transform:uppercase;">{{ $item->isFolder() ? 'FOLDER' : strtoupper(pathinfo($item->name, PATHINFO_EXTENSION)) }}</div>
                            <div>
                                <div style="font-weight:700;">{{ $item->name }}</div>
                                <div style="color:var(--muted); font-size:13px;">{{ $item->isFolder() ? '—' : $item->human_size }} • {{ $item->created_at->format('M d, Y H:i') }}</div>
                            </div>
                        </div>
                        <a class="btn small secondary" href="{{ route('files.show', $item) }}">Open</a>
                    </div>
                @empty
                    <div class="list-item" style="border-bottom:none;">No recent uploads yet.</div>
                @endforelse
            </div>

            <div class="card">
                <div style="font-weight:800; margin-bottom:8px;">Top types</div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    @forelse ($topTypes as $type)
                        <span class="chip">{{ $type->mime }} • {{ $type->total }}</span>
                    @empty
                        <span style="color:var(--muted);">No uploads yet.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
