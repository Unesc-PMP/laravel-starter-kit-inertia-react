<?php

declare(strict_types=1);

use App\Checkpoint\Checks\BunAuditCheck;
use Checkpoint\Checks\CheckResult;
use Illuminate\Support\Facades\Process;

it('warns when package.json is missing', function (): void {
    $basePath = sys_get_temp_dir().'/checkpoint-bun-audit-'.uniqid();

    mkdir($basePath, 0755, true);

    $result = (new BunAuditCheck($basePath))->run();

    expect($result->status)->toBe(CheckResult::WARN)
        ->and($result->message)->toContain('package.json');

    rmdir($basePath);
});

it('warns when bun.lock is missing', function (): void {
    $basePath = sys_get_temp_dir().'/checkpoint-bun-audit-'.uniqid();

    mkdir($basePath, 0755, true);
    file_put_contents($basePath.'/package.json', '{}');

    $result = (new BunAuditCheck($basePath))->run();

    expect($result->status)->toBe(CheckResult::WARN)
        ->and($result->message)->toContain('bun.lock');

    unlink($basePath.'/package.json');
    rmdir($basePath);
});

it('passes when bun audit reports no vulnerabilities', function (): void {
    $basePath = sys_get_temp_dir().'/checkpoint-bun-audit-'.uniqid();

    mkdir($basePath, 0755, true);
    file_put_contents($basePath.'/package.json', '{}');
    file_put_contents($basePath.'/bun.lock', '');

    Process::fake([
        '*' => Process::result(output: '{}', exitCode: 0),
    ]);

    $result = (new BunAuditCheck($basePath, 'bun'))->run();

    expect($result->status)->toBe(CheckResult::PASS)
        ->and($result->message)->toContain('No known CVEs');

    unlink($basePath.'/bun.lock');
    unlink($basePath.'/package.json');
    rmdir($basePath);
});

it('fails when bun audit reports critical vulnerabilities', function (): void {
    $basePath = sys_get_temp_dir().'/checkpoint-bun-audit-'.uniqid();

    mkdir($basePath, 0755, true);
    file_put_contents($basePath.'/package.json', '{}');
    file_put_contents($basePath.'/bun.lock', '');

    Process::fake([
        '*' => Process::result(
            output: json_encode([
                'vulnerabilities' => [
                    'lodash' => [
                        'severity' => 'critical',
                        'title' => 'Prototype Pollution',
                    ],
                ],
            ], JSON_THROW_ON_ERROR),
            exitCode: 1,
        ),
    ]);

    $result = (new BunAuditCheck($basePath, 'bun'))->run();

    expect($result->status)->toBe(CheckResult::FAIL)
        ->and($result->details)->toContain('[critical] lodash: Prototype Pollution');

    unlink($basePath.'/bun.lock');
    unlink($basePath.'/package.json');
    rmdir($basePath);
});
