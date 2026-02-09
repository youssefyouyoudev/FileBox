<x-app-layout>
<x-slot name="header">
    <h2 style="margin:0; font-size:20px; font-weight:700; color:var(--text);">Upload</h2>
</x-slot>

<style>
    .panel-title { font-size:18px; font-weight:700; margin:0 0 4px 0; }
    .panel-sub { margin:0; color:var(--muted); }
    .upload { border:1px dashed var(--border); border-radius:16px; padding:18px; background:var(--surface-alt); transition:.12s ease; }
    .upload.dragover { border-color: var(--accent); background: color-mix(in srgb, var(--surface-alt) 70%, transparent); }
    .upload input[type=file]{display:none;}
    .upload-label{display:flex;align-items:center;justify-content:space-between;gap:12px;cursor:pointer;}
    .upload-meta{display:flex;flex-direction:column;gap:4px;}
    .flex-between{display:flex;align-items:center;justify-content:space-between;gap:10px;}
    .preview-hint{font-size:13px;color:var(--muted);margin-top:8px;}
    .previews{display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;margin-top:12px;}
    .preview-card{border:1px solid var(--border);border-radius:12px;padding:8px;background:var(--surface-alt);display:flex;flex-direction:column;gap:6px;}
    .preview-img{width:100%;height:96px;object-fit:cover;border-radius:8px;border:1px solid var(--border);background:var(--surface);}
    .preview-file{height:96px;display:flex;align-items:center;justify-content:center;border-radius:8px;border:1px dashed var(--border);color:var(--text);font-weight:700;}
    .preview-name{font-size:13px;color:var(--muted);word-break:break-word;}
</style>

<div class="py-6">
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="card">
        <div class="panel-title">Upload to FileBox</div>
        <p class="panel-sub">Select files (JPG, PNG, PDF, DOCX, ZIP, RAR · max 5MB each). Optionally place them inside a folder.</p>

        <form data-upload-form data-ajax="false" action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data" style="margin-top:12px;">
            @csrf
            <div style="display:flex; gap:10px; align-items:flex-end; margin-bottom:10px; flex-wrap:wrap;">
                <div style="flex:1; min-width:220px;">
                    <label for="folder_name" class="panel-sub" style="display:block; margin-bottom:6px; color:var(--muted);">Folder (optional)</label>
                    <input name="folder_name" type="text" placeholder="e.g. Contracts" />
                    <div class="preview-hint">Files upload inside this folder. Leave blank for root.</div>
                </div>
            </div>

            <div class="upload" data-dropzone>
                <div class="upload-label">
                    <div class="upload-meta">
                        <strong>Drop files here</strong>
                        <span class="panel-sub">This form will submit traditionally.</span>
                    </div>
                    <span class="btn secondary" type="button">Browse</span>
                </div>
                <input name="files[]" type="file" multiple accept=".jpg,.jpeg,.png,.pdf,.docx,.zip,.rar">
            </div>

            <div class="preview-hint">Selected files preview (not yet uploaded):</div>
            <div class="previews" data-preview-list></div>

            <div class="flex-between" style="margin-top:12px;">
                <a class="btn secondary" href="{{ route('files.index') }}">Back</a>
                <button class="btn" type="submit">Upload</button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('[data-upload-form]');
        if (!form) return;
        const dropzone = form.querySelector('[data-dropzone]');
        const input = form.querySelector('input[type="file"]');
        const previewList = form.querySelector('[data-preview-list]');

        if (dropzone && input) {
            ['dragenter','dragover'].forEach(evt => dropzone.addEventListener(evt, e => { e.preventDefault(); dropzone.classList.add('dragover'); }));
            ['dragleave','drop'].forEach(evt => dropzone.addEventListener(evt, e => { e.preventDefault(); dropzone.classList.remove('dragover'); }));
            dropzone.addEventListener('drop', e => {
                if (!e.dataTransfer) return;
                const dt = new DataTransfer();
                Array.from(e.dataTransfer.files).forEach(file => dt.items.add(file));
                input.files = dt.files;
                render(previewList, input.files);
            });
            dropzone.addEventListener('click', () => input.click());
        }

        input?.addEventListener('change', () => render(previewList, input.files || []));

        function render(list, files) {
            if (!list) return;
            list.innerHTML = '';
            if (!files || !files.length) {
                list.innerHTML = '<div class="preview-hint">No files selected yet.</div>';
                return;
            }
            Array.from(files).forEach(file => {
                const card = document.createElement('div');
                card.className = 'preview-card';
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.className = 'preview-img';
                    img.alt = file.name;
                    card.appendChild(img);
                    const reader = new FileReader();
                    reader.onload = e => img.src = e.target?.result;
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
                list.appendChild(card);
            });
        }

        function formatSize(bytes) {
            if (bytes >= 1048576) return `${(bytes / 1048576).toFixed(2)} MB`;
            return `${(bytes / 1024).toFixed(1)} KB`;
        }
    });
</script>

</x-app-layout>
