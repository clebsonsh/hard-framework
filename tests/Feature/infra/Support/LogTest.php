<?php

declare(strict_types=1);

use Infra\Support\Log;

describe('cleanupOldLogs', function () {
    it('should delete log files older than 7 days upon initialization', function () {
        $sevenDayAgo = date('Ymd', strtotime('-7 days'));
        $filePath = storage_path()."/logs/app-$sevenDayAgo.log";

        touch($filePath);
        Log::getInstance();

        expect(file_exists($filePath))->toBeFalse();
    });
});

describe('helper functions', function () {
    it('should write an INFO level message to the log file', function () {
        $message = 'test info message';

        info($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, "INFO: $message"))->toBeTrue();
    });

    it('should write a DEBUG level message to the log file', function () {
        $message = 'test debug message';

        debug($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, "DEBUG: $message"))->toBeTrue();
    });

    it('should write a WARN level message to the log file', function () {
        $message = 'test warn message';

        warn($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, "WARN: $message"))->toBeTrue();
    });

    it('should write an ERROR level message to the log file', function () {
        $message = 'test error message';

        error($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, "ERROR: $message"))->toBeTrue();
    });
});
