<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BillingController;
use App\Models\File;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('landing'))->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        $filesQuery = File::where('user_id', $user->id);
        $latestFile = (clone $filesQuery)->where('mime', '!=', 'folder')->latest()->first();

        $stats = [
            'files' => (clone $filesQuery)->where('mime', '!=', 'folder')->count(),
            'folders' => (clone $filesQuery)->where('mime', 'folder')->count(),
            'used' => $user->storageUsed(),
            'limit' => $user->storage_limit,
            'uploads_last_7' => (clone $filesQuery)->where('created_at', '>=', now()->subDays(7))->count(),
            'avg_size' => (clone $filesQuery)->where('mime', '!=', 'folder')->avg('size') ?? 0,
            'last_upload' => $latestFile?->created_at,
        ];

        $topTypes = (clone $filesQuery)
            ->where('mime', '!=', 'folder')
            ->selectRaw('mime, count(*) as total')
            ->groupBy('mime')
            ->orderByDesc('total')
            ->limit(4)
            ->get();

        $recent = (clone $filesQuery)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'user', 'recent', 'topTypes'));
    })->name('dashboard');

    Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::post('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
    Route::get('files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::resource('files', FileController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
