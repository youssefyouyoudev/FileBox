<x-app-layout>
<x-slot name="header">
    <h2 style="margin:0; font-size:20px; font-weight:700; color:var(--text);">File</h2>
</x-slot>

<style>
    .panel-title { font-size:18px; font-weight:700; margin:0 0 4px 0; }
    .panel-sub { margin:0; color:var(--muted); }
    .actions{display:flex;align-items:center;gap:8px;}
    .btn.danger{background:transparent;border-color:rgba(244,91,105,0.55);color:var(--text);}
    .grid { display:grid; grid-template-columns:1.2fr 1fr; gap:20px; }
    .muted{color:var(--muted);}
    .icon-tile{width:88px;height:88px;font-size:22px;border-radius:12px;display:inline-flex;align-items:center;justify-content:center;font-weight:700;color:var(--text);background:linear-gradient(135deg,rgba(124,93,255,0.16),rgba(59,200,246,0.14));border:1px solid var(--border);}
</style>

<div class="py-6">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card">
        <div class="actions" style="margin-bottom:12px; justify-content:space-between;">
            <div>
                <div class="panel-title">{{ $file->name }}</div>
                <p class="panel-sub">Uploaded {{ $file->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="actions">
                <a class="btn secondary" href="{{ route('files.index') }}">Back</a>
                @unless ($file->isFolder())
                    <a class="btn secondary" href="{{ route('files.download', $file) }}">Download</a>
                @endunless
                <form action="{{ route('files.destroy', $file) }}" method="POST" onsubmit="return confirm('Delete this file?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn danger" type="submit">Delete</button>
                </form>
            </div>
        </div>

        <div class="grid">
            <div class="card" style="box-shadow:none;">
                @if ($file->isFolder())
                    <div style="display:flex; align-items:center; justify-content:center; min-height: 320px; border: 1px dashed var(--border); border-radius: 12px; text-align:center; color:var(--muted);">
                        <div>
                            <div class="icon-tile" style="margin:0 auto 12px;">FOLDER</div>
                            <div>This is a folder entry. Upload files with this folder selected to keep them grouped.</div>
                        </div>
                    </div>
                @elseif ($file->isImage())
                    <img src="{{ $file->url }}" alt="{{ $file->name }}" style="width: 100%; border-radius: 12px; border: 1px solid var(--border);">
                @elseif (str_contains($file->mime, 'pdf'))
                    <iframe src="{{ $file->url }}" title="{{ $file->name }}" style="width: 100%; height: 420px; border: 1px solid var(--border); border-radius: 12px;"></iframe>
                @else
                    <div style="display:flex; align-items:center; justify-content:center; min-height: 320px; border: 1px dashed var(--border); border-radius: 12px;">
                        <div class="icon-tile">{{ strtoupper(pathinfo($file->name, PATHINFO_EXTENSION)) }}</div>
                    </div>
                @endif
            </div>

            <div class="card" style="box-shadow:none;">
                <div class="panel-title" style="margin-bottom: 10px;">Details</div>
                <div style="display:grid; gap:10px;">
                    <div><span class="muted">Name</span><div>{{ $file->name }}</div></div>
                    <div><span class="muted">Path</span><div>{{ $file->path }}</div></div>
                    <div><span class="muted">Size</span><div>{{ $file->isFolder() ? '—' : $file->human_size }}</div></div>
                    <div><span class="muted">Type</span><div>{{ $file->isFolder() ? 'folder' : $file->mime }}</div></div>
                    @unless ($file->isFolder())
                        <div><span class="muted">URL</span><div><a class="btn secondary" href="{{ $file->url }}" target="_blank">Open in new tab</a></div></div>
                    @endunless
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</x-app-layout>
