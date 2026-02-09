<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="margin:0; font-size:22px; font-weight:800; color:var(--text);">Profile</h2>
                <div style="color:var(--muted);">Manage your account, security, and billing details.</div>
            </div>
                <form method="POST" action="{{ route('billing.portal') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="return_url" value="{{ route('profile.edit') }}">
                    <button class="btn secondary" type="submit">Manage billing</button>
                </form>
        </div>
    </x-slot>

    <style>
        .profile-grid { display:grid; gap:14px; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); }
        .field-row { display:grid; gap:8px; }
        .label { color:var(--muted); font-weight:700; }
        .hint { color:var(--muted); font-size:13px; }
        .chip { padding:6px 10px; border-radius:999px; border:1px solid var(--border); background:var(--surface-alt); color:var(--text); font-weight:700; font-size:12px; }
    </style>

    <div style="display:grid; gap:16px;">
        @if (session('status') === 'profile-updated')
            <div class="card" style="border-color: var(--accent);">Profile updated.</div>
        @endif

        <div class="card" style="display:grid; gap:12px;">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div>
                    <div class="panel-title">Account info</div>
                    <div class="hint">Keep your contact details current.</div>
                </div>
                <span class="chip">Verified: {{ $user->email_verified_at ? 'Yes' : 'No' }}</span>
            </div>
            <form method="POST" action="{{ route('profile.update') }}" class="profile-grid">
                @csrf
                @method('PATCH')
                <div class="field-row">
                    <label class="label" for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
                </div>
                <div class="field-row">
                    <label class="label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
                    <div class="hint">We use this for notifications and billing receipts.</div>
                </div>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button class="btn" type="submit">Save changes</button>
                    <a class="btn secondary" href="{{ route('dashboard') }}">Cancel</a>
                </div>
            </form>
        </div>

        <div class="card" style="display:grid; gap:12px;">
            <div>
                <div class="panel-title">Password</div>
                <div class="hint">Set a strong password to keep your files secure.</div>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="profile-grid">
                @csrf
                @method('PUT')
                <div class="field-row">
                    <label class="label" for="current_password">Current password</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password" required />
                </div>
                <div class="field-row">
                    <label class="label" for="password">New password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required />
                </div>
                <div class="field-row">
                    <label class="label" for="password_confirmation">Confirm new password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required />
                </div>
                <div>
                    <button class="btn" type="submit">Update password</button>
                </div>
            </form>
        </div>

        <div class="card" style="display:grid; gap:12px; border:1px solid rgba(244,91,105,0.5);">
            <div>
                <div class="panel-title">Delete account</div>
                <div class="hint">Permanently remove your account and all files. This action cannot be undone.</div>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This will remove all files.');" style="display:flex; gap:10px; flex-wrap:wrap;">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit">Delete account</button>
                <a class="btn secondary" href="{{ route('dashboard') }}">Keep account</a>
            </form>
        </div>
    </div>
</x-app-layout>
