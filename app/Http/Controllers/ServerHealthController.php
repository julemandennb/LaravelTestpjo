<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;

class ServerHealthController extends Controller
{
    public function __invoke(Request $request)
    {
        $checks = [
            'php_version' => $this->versionCheck(
                PHP_VERSION,
                version_compare(PHP_VERSION, '8.2.0', '>='),
            ),
            'laravel_version' => $this->versionCheck(app()->version(), true),
        ];

        try {
            DB::select('SELECT 1');
            $checks['database'] = $this->check('OK', 'Connection is available.');
        } catch (\Throwable $e) {
            $checks['database'] = $this->check('FAILED', 'Connection is unavailable.');
        }

        try {
            Redis::connection()->ping();
            $checks['redis'] = $this->check('OK', 'Connection is available.');
        } catch (\Throwable $e) {
            $checks['redis'] = $this->check('FAILED', 'Connection is unavailable.');
        }

        $checks['queue'] = $this->queueCheck();

        $checks['storage'] = $this->writableDirectoryCheck(storage_path());
        $checks['bootstrap_cache'] = $this->writableDirectoryCheck(base_path('bootstrap/cache'));

        $statuses = array_column($checks, 'status');
        $hasFailures = in_array('FAILED', $statuses, true);
        $hasWarnings = in_array('WARNING', $statuses, true);
        $overallStatus = $hasFailures ? 'FAILED' : ($hasWarnings ? 'WARNING' : 'OK');

        $response = [
            'status' => $overallStatus,
            'healthy' => ! $hasFailures,
            'checks' => $checks,
        ];

        if ($request->expectsJson()) {
            return response()->json($response, $hasFailures ? 503 : 200);
        }

        return Inertia::render('ServerHealth/index', $response);
    }

    private function queueCheck(): array
    {
        $connectionName = config('queue.default');
        $connectionConfig = config('queue.connections', [])[$connectionName] ?? [];
        $driver = $connectionConfig['driver'] ?? $connectionName;

        try {
            app('queue')->connection($connectionName);

            if ($driver === 'sync') {
                return $this->check('OK', 'Synchronous queue is configured; no worker is required.', [
                    'driver' => $driver,
                    'worker' => 'NOT_APPLICABLE',
                ]);
            }

            if ($driver === 'database') {
                DB::connection($connectionConfig['connection'] ?? config('database.default'))
                    ->select('SELECT 1');

                return $this->check('WARNING', 'Queue storage is available, but worker liveness is not verifiable from HTTP.', [
                    'driver' => $driver,
                    'worker' => 'UNKNOWN',
                ]);
            }

            if ($driver === 'redis') {
                Redis::connection($connectionConfig['connection'] ?? 'default')->ping();

                return $this->check('WARNING', 'Queue connection is available, but worker liveness is not verifiable from HTTP.', [
                    'driver' => $driver,
                    'worker' => 'UNKNOWN',
                ]);
            }

            return $this->check('WARNING', 'Queue configuration is valid, but this driver cannot be probed without dispatching work.', [
                'driver' => $driver,
                'worker' => 'UNKNOWN',
            ]);
        } catch (\Throwable $e) {
            return $this->check('FAILED', 'Queue connection is unavailable.', [
                'driver' => $driver,
                'worker' => 'UNKNOWN',
            ]);
        }
    }

    private function writableDirectoryCheck(string $path): array
    {
        $writable = is_dir($path) && is_writable($path);

        return $this->check(
            $writable ? 'OK' : 'FAILED',
            $writable ? 'Directory is writable.' : 'Directory is not writable.',
        );
    }

    private function versionCheck(string $version, bool $supported): array
    {
        return $this->check($supported ? 'OK' : 'FAILED', $version, ['version' => $version]);
    }

    private function check(string $status, string $message, array $details = []): array
    {
        return array_merge(['status' => $status, 'message' => $message], $details);
    }
}
