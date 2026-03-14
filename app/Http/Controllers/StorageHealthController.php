<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class StorageHealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
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

        $isSymlink = @is_link($publicStoragePath);
        $isDirectory = is_dir($publicStoragePath);

        $checks = [
            'disk_public_root_exists' => is_dir($publicRoot),
            'disk_public_root_writable' => is_writable($publicRoot),
            'public_storage_path_exists' => $publicStorageExists,
            'public_storage_path_is_symlink' => $isSymlink,
            'probe_write' => $writeSucceeded,
            'probe_exists_after_write' => $existsAfterWrite,
            'probe_read_matches' => $readBack === $probePayload,
            'probe_delete' => $deleteSucceeded,
        ];

        $ok = ! in_array(false, $checks, true);

        return response()->json([
            'ok' => $ok,
            'timestamp' => now()->toIso8601String(),
            'config' => [
                'app_env' => app()->environment(),
                'app_url' => config('app.url'),
                'filesystem_default' => config('filesystems.default'),
                'filesystem_public_root' => $publicRoot,
                'public_storage_path' => $publicStoragePath,
            ],
            'checks' => $checks,
            'hints' => [
                'Si probe_write=false, vérifier les permissions de storage/app/public.',
                'Si public_storage_path_exists=false, exécuter: php artisan storage:link.',
                'Si probe_read_matches=false, vérifier le driver du disque public.',
            ],
        ], $ok ? 200 : 500);
    }
}
