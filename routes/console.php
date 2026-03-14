<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('storage:health', function () {
    $disk = Storage::disk('public');
    $publicRoot = $disk->path('');
    $publicStoragePath = public_path('storage');
    $publicStorageExists = file_exists($publicStoragePath);

    $probeFile = 'diagnostics/storage-health-' . now()->format('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.txt';
    $probePayload = 'storage-health:' . now()->toIso8601String();

    $writeSucceeded = (bool) $disk->put($probeFile, $probePayload);
    $existsAfterWrite = $writeSucceeded ? $disk->exists($probeFile) : false;
    $readBack = $existsAfterWrite ? $disk->get($probeFile) : null;
    $deleteSucceeded = $existsAfterWrite ? $disk->delete($probeFile) : null;

    $checks = [
        'disk_public_root_exists' => is_dir($publicRoot),
        'disk_public_root_writable' => is_writable($publicRoot),
        'public_storage_path_exists' => $publicStorageExists,
        'probe_write' => $writeSucceeded,
        'probe_exists_after_write' => $existsAfterWrite,
        'probe_read_matches' => $readBack === $probePayload,
        'probe_delete' => $deleteSucceeded,
    ];

    foreach ($checks as $label => $status) {
        $this->line(($status ? '✅' : '❌') . ' ' . $label);
    }

    $ok = ! in_array(false, $checks, true);

    $this->newLine();
    $this->line('filesystem.default=' . config('filesystems.default'));
    $this->line('app.env=' . app()->environment());
    $this->line('app.url=' . config('app.url'));
    $this->line('disk.public.root=' . $publicRoot);
    $this->line('public.storage.path=' . $publicStoragePath);

    if (! $ok) {
        $this->newLine();
        $this->warn('Diagnostic KO: vérifier permissions storage/app/public et le lien public/storage.');

        return self::FAILURE;
    }

    $this->newLine();
    $this->info('Diagnostic OK: stockage public opérationnel.');

    return self::SUCCESS;
})->purpose('Check public storage write/read/delete and symlink availability');
