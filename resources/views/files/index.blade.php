<x-app-layout>
<x-slot name="header">
    <div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;">
        <div>
            <h2 style="margin:0; font-size:22px; font-weight:800; color:var(--text); letter-spacing:0.2px;">Files workspace</h2>
            <div style="color:var(--muted); font-size:14px;">Manage uploads, folders, and downloads in one place.</div>
        </div>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <a class="btn secondary" href="{{ route('files.create') }}">Upload</a>
            <a class="btn" href="{{ route('files.index') }}">Refresh</a>
        </div>
    </div>
</x-slot>

<style>
    .page-shell { background: radial-gradient(120% 120% at 0% 0%, rgba(124,93,255,0.12), transparent), radial-gradient(140% 140% at 100% 10%, rgba(59,200,246,0.12), transparent), var(--bg); padding: 8px 0 20px; }
    .grid { display:grid; grid-template-columns:280px 1fr; gap:16px; align-items:start; }
    .panel-title { font-size:18px; font-weight:800; margin:0 0 4px 0; letter-spacing:0.1px; }
    .panel-sub { margin:0; color:var(--muted); }
    .muted{color:var(--muted);}
    .upload { border:1px dashed var(--border); border-radius:18px; padding:18px; background:var(--surface-alt); transition:.15s ease; box-shadow:0 18px 40px rgba(0,0,0,0.08); }
    .upload.dragover { border-color: var(--accent); background: color-mix(in srgb, var(--surface-alt) 70%, transparent); box-shadow:0 18px 44px rgba(124,93,255,0.18); }
    .upload input[type=file]{display:none;}
    .upload-label{display:flex;align-items:center;justify-content:space-between;gap:12px;cursor:pointer;}
    .upload-meta{display:flex;flex-direction:column;gap:4px;}
    .btn.danger{background:transparent;border-color:rgba(244,91,105,0.55);color:var(--text);}
    .flex-between{display:flex;align-items:center;justify-content:space-between;gap:10px;}
    .status-line{font-size:13px;color:var(--muted);margin-top:6px;}
    .progress{width:100%;background:var(--surface-alt);border-radius:999px;border:1px solid var(--border);overflow:hidden;height:10px;}
    .progress-bar{height:100%;width:0;background:linear-gradient(120deg,var(--accent),var(--accent-2));transition:width .1s linear;}
    table{width:100%;border-collapse:collapse;margin-top:10px;table-layout:fixed;}
    th,td{padding:12px 10px;border-bottom:1px solid color-mix(in srgb, var(--border) 70%, transparent);text-align:left;font-size:14px;color:var(--text);}
    th{color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:0.4px;background:color-mix(in srgb, var(--surface) 70%, transparent);}
        .col-preview { width: 72px; }
        .col-name { width: 44%; }
        .col-size { width: 80px; }
        .col-type { width: 110px; }
        .col-date { width: 140px; }
        .col-actions { width: 220px; text-align:right; }
    tr:hover td{background:color-mix(in srgb, var(--surface-alt) 70%, transparent);}
    .badge{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;font-size:12px;border:1px solid var(--border);background:color-mix(in srgb, var(--surface) 80%, transparent);color:var(--text);box-shadow:0 8px 20px rgba(0,0,0,0.12);}
    .thumb{width:48px;height:48px;border-radius:14px;object-fit:cover;border:1px solid var(--border);background:var(--surface-alt);display:inline-block;}
    .icon-tile{width:48px;height:48px;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;font-weight:800;color:var(--text);background:linear-gradient(135deg,rgba(124,93,255,0.18),rgba(59,200,246,0.16));border:1px solid var(--border);}
    .alert{border-radius:12px;padding:12px 14px;border:1px solid var(--border);background:var(--surface-alt);color:var(--text);margin-bottom:12px;box-shadow:0 14px 30px rgba(0,0,0,0.12);}
    .alert.success{border-color:rgba(34,197,94,0.5);}
    .alert.error{border-color:rgba(239,68,68,0.5);}
    .previews{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;margin-top:12px;}
    .preview-card{border:1px solid var(--border);border-radius:12px;padding:8px;background:var(--surface-alt);display:flex;flex-direction:column;gap:6px;box-shadow:0 10px 28px rgba(0,0,0,0.14);}
    .preview-img{width:100%;height:96px;object-fit:cover;border-radius:8px;border:1px solid var(--border);background:var(--surface);}
    .preview-file{height:96px;display:flex;align-items:center;justify-content:center;border-radius:8px;border:1px dashed var(--border);color:var(--text);font-weight:700;}
    .preview-name{font-size:13px;color:var(--muted);word-break:break-word;}
    .preview-hint{font-size:13px;color:var(--muted);margin-top:8px;}
    .usage-bar{height:10px;border-radius:999px;background:var(--surface-alt);border:1px solid var(--border);overflow:hidden;}
    .usage-fill{height:100%;background:linear-gradient(90deg,var(--accent),var(--accent-2));}
    .card-surface { border-radius:20px; border:1px solid var(--border); background:color-mix(in srgb, var(--surface) 80%, transparent); box-shadow:0 24px 60px rgba(0,0,0,0.25); padding:18px; }
    .pill { padding:6px 12px; border-radius:999px; background:color-mix(in srgb, var(--surface-alt) 70%, transparent); border:1px solid var(--border); color:var(--muted); font-weight:700; font-size:12px; }
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:10px; }
    .stat-card { border:1px solid var(--border); border-radius:14px; padding:12px; background:color-mix(in srgb, var(--surface) 85%, transparent); box-shadow:0 14px 36px rgba(0,0,0,0.18); }
    .stat-label { color:var(--muted); font-weight:700; font-size:12px; letter-spacing:0.2px; }
    .stat-value { font-size:22px; font-weight:800; margin-top:4px; }
    .section-title { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:10px; }
    .name-cell { max-width: 320px; }
    .name-text { font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .truncate { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
    @media (max-width:900px){.grid{grid-template-columns:1fr;}}
</style>

<div class="page-shell">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    @php
        $folderCount = \App\Models\File::where('user_id', auth()->id())->where('mime', 'folder')->count();
        $fileCount = \App\Models\File::where('user_id', auth()->id())->where('mime', '!=', 'folder')->count();
    @endphp

    @if (session('status'))
        <div class="alert success">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert error">
            <strong>Upload failed:</strong>
            <ul style="margin: 8px 0 0 16px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="margin-bottom:12px; border-radius:22px; border:1px solid var(--border); box-shadow:0 24px 60px rgba(0,0,0,0.32); background:linear-gradient(145deg, color-mix(in srgb, var(--surface) 82%, transparent), color-mix(in srgb, var(--surface-alt) 80%, transparent));">
        <div class="flex-between" style="margin-bottom:12px; align-items:flex-start; gap:12px;">
            <div>
                <div class="panel-title">Storage overview</div>
                <div class="panel-sub">{{ number_format($user->storageUsed() / 1048576, 2) }} MB of {{ number_format($user->storage_limit / 1048576, 2) }} MB</div>
            </div>
            <a class="btn secondary" href="#">Upgrade</a>
        </div>
        <div class="usage-bar" style="margin-top:4px; height:12px;">
            <div class="usage-fill" style="width: {{ min(100, $user->storageUsagePercent()) }}%;"></div>
        </div>
        <div class="stats-grid" style="margin-top:12px;">
            <div class="stat-card">
                <div class="stat-label">Usage</div>
                <div class="stat-value">{{ $user->storageUsagePercent() }}%</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Files</div>
                <div class="stat-value">{{ $fileCount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Folders</div>
                <div class="stat-value">{{ $folderCount }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Remaining</div>
                <div class="stat-value">{{ number_format($user->storageRemaining() / 1048576, 2) }} MB</div>
            </div>
        </div>
    </div>

    <div class="grid">
    <div class="card">
        <div class="section-title">
            <div>
                <div class="panel-title">Upload files</div>
                <p class="panel-sub">Drag & drop or browse. Allowed: JPG, PNG, PDF, DOCX, ZIP, RAR (max 5MB each).</p>
            </div>
            <span class="pill">Supports folders</span>
        </div>

        <form data-upload-form data-ajax="true" action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" style="margin-top:12px;">
            @csrf
            <div style="display:flex; gap:10px; align-items:flex-end; margin-bottom:14px; flex-wrap:wrap;">
                <div style="flex:1; min-width:220px;">
                    <label for="folder_name" class="panel-sub" style="display:block; margin-bottom:6px; color:var(--muted);">Folder (optional)</label>
                    <input data-folder-input id="folder_name" name="folder_name" type="text" placeholder="e.g. Design handoff" />
                    <div class="preview-hint">Files upload inside this folder. Leave blank for root.</div>
                </div>
                <button class="btn secondary" type="submit" style="align-self:flex-start;">Create folder</button>
            </div>
            <div class="upload" data-dropzone>
                <div class="upload-label">
                    <div class="upload-meta">
                        <strong>Drop files here</strong>
                        <span class="muted">You can select multiple files at once.</span>
                    </div>
                    <span class="btn secondary" type="button">Browse</span>
                </div>
                <input id="files" name="files[]" type="file" multiple accept=".jpg,.jpeg,.png,.pdf,.docx,.zip,.rar">
            </div>

            <div class="preview-hint">Selected files preview (not yet uploaded):</div>
            <div class="previews" data-preview-list></div>

            <div class="flex-between" style="margin-top:12px;">
                <div class="status-line" data-status-line>Ready to upload.</div>
                <button class="btn" type="submit">Upload</button>
            </div>

            <div class="hidden" data-progress style="margin-top:10px;">
                <div class="progress"><div class="progress-bar" data-progress-bar></div></div>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="flex-between" style="margin-bottom:8px; align-items:flex-start; gap:10px;">
            <div>
                <div class="panel-title">Your files</div>
                <p class="panel-sub">{{ $files->total() }} file(s) in this view • {{ $folderCount }} folders total</p>
            </div>
            <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                <form method="GET" action="{{ route('files.index') }}" style="display:flex; gap:6px; align-items:center;">
                    <span class="pill" style="background:transparent; border:none; padding:0;">Per page</span>
                    <select name="per_page" onchange="this.form.submit()" style="padding:8px 10px; border-radius:10px; border:1px solid var(--border); background:var(--surface-alt); color:var(--text); font-weight:700;">
                        @foreach ([10,20,50] as $size)
                            <option value="{{ $size }}" {{ ($perPage ?? 10) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="pill">Sorted by newest</span>
                <a class="btn secondary" href="{{ route('files.create') }}">Open upload page</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-preview">Preview</th>
                    <th class="col-name">Name</th>
                    <th class="col-size">Size</th>
                    <th class="col-type">Type</th>
                    <th class="col-date">Added</th>
                    <th class="col-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($files as $file)
                    <tr>
                        <td class="col-preview">
                            @if ($file->isFolder())
                                <div class="icon-tile">FOLDER</div>
                            @elseif ($file->isImage())
                                <img class="thumb" src="{{ $file->url }}" alt="{{ $file->name }}">
                            @else
                                <div class="icon-tile">{{ strtoupper(pathinfo($file->name, PATHINFO_EXTENSION)) }}</div>
                            @endif
                        </td>
                        <td class="name-cell col-name">
                            <div class="name-text" title="{{ $file->name }}">{{ $file->name }}</div>
                            <div class="muted truncate" title="{{ $file->path }}">{{ $file->path }}</div>
                        </td>
                        <td class="col-size">{{ $file->isFolder() ? '—' : $file->human_size }}</td>
                        <td class="col-type"><span class="badge">{{ $file->isFolder() ? 'folder' : $file->mime }}</span></td>
                        <td class="col-date">{{ $file->created_at->format('M d, Y H:i') }}</td>
                        <td class="col-actions">
                            <div class="actions" style="justify-content:flex-end;">
                                <a class="btn secondary" href="{{ route('files.show', $file) }}">View</a>
                                @unless ($file->isFolder())
                                    <a class="btn secondary" href="{{ route('files.download', $file) }}">Download</a>
                                @endunless
                                <form action="{{ route('files.destroy', $file) }}" method="POST" onsubmit="return confirm('Delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="muted">No files uploaded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:12px;">
            {{ $files->links() }}
        </div>
    </div>

</div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const forms = document.querySelectorAll('[data-upload-form]');
        forms.forEach(form => {
            const dropzone = form.querySelector('[data-dropzone]');
            const input = form.querySelector('input[type="file"]');
            const folderInput = form.querySelector('[data-folder-input]');
            const progress = form.querySelector('[data-progress]');
            const progressBar = form.querySelector('[data-progress-bar]');
            const statusLine = form.querySelector('[data-status-line]');
            const previewList = form.querySelector('[data-preview-list]');
            const isAjax = form.dataset.ajax === 'true';

            const setStatus = (text, isError = false) => {
                if (!statusLine) return;
                statusLine.textContent = text;
                statusLine.style.color = isError ? '#fecaca' : 'var(--muted)';
            };

            if (dropzone && input) {
                ['dragenter','dragover'].forEach(evt => dropzone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropzone.classList.add('dragover');
                }));
                ['dragleave','drop'].forEach(evt => dropzone.addEventListener(evt, e => {
                    e.preventDefault();
                    dropzone.classList.remove('dragover');
                }));
                dropzone.addEventListener('drop', e => {
                    if (!e.dataTransfer) return;
                    const dt = new DataTransfer();
                    Array.from(e.dataTransfer.files).forEach(file => dt.items.add(file));
                    input.files = dt.files;
                    handlePreview(input.files);
                    setStatus(`${input.files.length} file(s) ready to upload`);
                });
                dropzone.addEventListener('click', () => input.click());
            }

            input?.addEventListener('change', () => {
                handlePreview(input.files || []);
                if (input.files?.length) {
                    setStatus(`${input.files.length} file(s) ready to upload`);
                }
            });

            form.addEventListener('submit', event => {
                if (!isAjax) {
                    return;
                }
                event.preventDefault();

                const files = input?.files;
                const folderName = folderInput?.value?.trim() || '';
                if ((!files || !files.length) && !folderName) {
                    setStatus('Add files or a folder name first.', true);
                    return;
                }

                const data = new FormData(form);
                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('Accept', 'application/json');

                if (progress) progress.classList.remove('hidden');
                if (progressBar) progressBar.style.width = '0%';
                setStatus('Uploading...');

                xhr.upload.addEventListener('progress', e => {
                    if (!e.lengthComputable || !progressBar) return;
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = `${percent}%`;
                    setStatus(`Uploading... ${percent}%`);
                });

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        setStatus('Upload complete, refreshing...');
                        window.location.reload();
                    } else {
                        setStatus('Upload failed. Check your files and try again.', true);
                    }
                };

                xhr.onerror = () => setStatus('Network error while uploading.', true);

                xhr.send(data);
            });

            function handlePreview(files) {
                if (!previewList) return;
                previewList.innerHTML = '';
                if (!files || !files.length) {
                    previewList.innerHTML = '<div class="preview-hint">No files selected yet.</div>';
                    return;
                }

                Array.from(files).forEach(file => {
                    const card = document.createElement('div');
                    card.className = 'preview-card';

                    const isImage = file.type.startsWith('image/');
                    if (isImage) {
                        const img = document.createElement('img');
                        img.className = 'preview-img';
                        img.alt = file.name;
                        card.appendChild(img);

                        const reader = new FileReader();
                        reader.onload = e => { img.src = e.target?.result; };
                        reader.readAsDataURL(file);
                    } else {
                        const tile = document.createElement('div');
                        tile.className = 'preview-file';
                        tile.textContent = (file.name.split('.').pop() || '').toUpperCase() || 'FILE';
                        card.appendChild(tile);
                    }

                    const meta = document.createElement('div');
                    meta.className = 'preview-name';
                    meta.textContent = `${file.name} (${formatSize(file.size)})`;
                    card.appendChild(meta);

                    previewList.appendChild(card);
                });
            }

            function formatSize(bytes) {
                if (bytes >= 1048576) return `${(bytes / 1048576).toFixed(2)} MB`;
                return `${(bytes / 1024).toFixed(1)} KB`;
            }
        });
    });
</script>
</x-app-layout>
