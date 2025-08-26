<?php

declare(strict_types=1);

describe('Log', function () {
    it('put info message in log file', function () {
        $message = 'test info message';

        info($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, $message))->toBeTrue();
    });

    it('put debug message in log file', function () {
        $message = 'test debug message';

        debug($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, $message))->toBeTrue();
    });

    it('put warn message in log file', function () {
        $message = 'test warn message';

        warn($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, $message))->toBeTrue();
    });

    it('put error message in log file', function () {
        $message = 'test error message';

        error($message);

        $today = date('Ymd');
        $logFilePath = storage_path()."/logs/app-$today.log";
        $logFile = file_get_contents($logFilePath);

        expect(str_contains($logFile, $message))->toBeTrue();
    });
});
