<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $perPage = (int) request('per_page', 10);
        $perPage = in_array($perPage, [10, 20, 50]) ? $perPage : 10;

        $files = File::where('user_id', auth()->id())
            ->latest()
            ->paginate($perPage)
            ->appends(['per_page' => $perPage]);

        return view('files.index', [
            'files' => $files,
            'user' => auth()->user(),
            'perPage' => $perPage,
        ]);
    }

    public function create(): View
    {
        return view('files.create');
    }

    public function store(FileRequest $request): RedirectResponse|Response
    {
        $uploads = $request->file('files', []);
        $folderName = trim((string) $request->input('folder_name'));
        $cleanFolder = $folderName !== '' ? preg_replace('#[\\/]+#', ' ', $folderName) : '';
        $safeFolder = $cleanFolder !== null ? trim($cleanFolder) : trim(str_replace(['\\', '/'], ' ', $folderName));
        $basePath = 'uploads' . ($safeFolder ? '/' . Str::slug($safeFolder, '-') : '');

        $totalSize = collect($uploads)->sum(fn ($file) => $file->getSize());
        $user = $request->user();

        if ($totalSize > $user->storageRemaining()) {
            $message = 'Upload exceeds your storage limit. Please upgrade or delete files.';
            if ($request->wantsJson()) {
                return response(['message' => $message], 422);
            }
            return back()->withErrors($message);
        }

        $stored = collect();

        if ($safeFolder && empty($uploads)) {
            Storage::disk('public')->makeDirectory($basePath);
            $stored->push(File::create([
                'name' => $safeFolder,
                'path' => $basePath,
                'size' => 0,
                'mime' => 'folder',
                'user_id' => $user->id,
            ]));

            $message = 'Folder created.';
            return $request->wantsJson()
                ? response(['message' => $message, 'files' => $stored], 201)
                : redirect()->route('files.index')->with('status', $message);
        }

        foreach ($uploads as $upload) {
            $path = $upload->store($basePath, 'public');

            $stored->push(File::create([
                'name' => $upload->getClientOriginalName(),
                'path' => $path,
                'size' => $upload->getSize(),
                'mime' => $upload->getClientMimeType(),
                'user_id' => $user->id,
            ]));
        }

        if ($totalSize > 0) {
            $user->addStorageUsage($totalSize);
        }

        $message = 'Uploaded ' . $stored->count() . ' file' . ($stored->count() === 1 ? '' : 's') . '.';

        if ($request->wantsJson()) {
            return response([
                'message' => $message,
                'files' => $stored,
            ], 201);
        }

        return redirect()->route('files.index')->with('status', $message);
    }

    public function show(File $file): View
    {
        $this->authorizeFile($file);
        $this->ensureExists($file);

        return view('files.show', compact('file'));
    }

    public function edit(File $file): RedirectResponse
    {
        $this->authorizeFile($file);
        return redirect()->route('files.show', $file);
    }

    public function update(Request $request, File $file): RedirectResponse
    {
        $this->authorizeFile($file);
        return redirect()->route('files.show', $file)->with('status', 'Updates are not required for files.');
    }

    public function destroy(File $file): RedirectResponse
    {
        $this->authorizeFile($file);
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $size = $file->size;
        $file->delete();

        $file->user?->subtractStorageUsage($size);

        return back()->with('status', 'File deleted.');
    }

    public function download(File $file)
    {
        $this->authorizeFile($file);
        $this->ensureExists($file);

        abort_if($file->isFolder(), 400, 'Cannot download a folder.');

        return Storage::disk('public')->download($file->path, $file->name);
    }

    protected function ensureExists(File $file): void
    {
        if ($file->isFolder()) {
            return;
        }

        if (! Storage::disk('public')->exists($file->path)) {
            abort(404, 'File missing from storage.');
        }
    }

    protected function authorizeFile(File $file): void
    {
        abort_if($file->user_id !== auth()->id(), 403);
    }
}
