<?php

declare(strict_types=1);

namespace Infra\Support;

use RuntimeException;

class Log
{
    const LEVEL_INFO = 'INFO';

    const LEVEL_DEBUG = 'DEBUG';

    const LEVEL_WARN = 'WARN';

    const LEVEL_ERROR = 'ERROR';

    private static ?self $instance = null;

    private string $filePath;

    /** @var resource */
    private $fileHandle;

    private function __construct()
    {
        $logsDirectory = storage_path().'/logs';
        if (! file_exists($logsDirectory)) {
            // @codeCoverageIgnoreStart
            mkdir($logsDirectory, 0755, true);
            // @codeCoverageIgnoreEnd
        }

        $today = date('Ymd');
        $this->filePath = $logsDirectory."/app-{$today}.log";

        /** @todo delete old log files */
        $fileHandle = fopen($this->filePath, 'a');

        if (! $fileHandle) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException("Unable to open log file: $this->filePath");
            // @codeCoverageIgnoreEnd
        }

        $this->fileHandle = $fileHandle;

        $this->cleanupOldLogs($logsDirectory);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function info(string $message): void
    {
        fwrite($this->fileHandle, $this->buildMessage($message, self::LEVEL_INFO));
    }

    public function debug(string $message): void
    {
        fwrite($this->fileHandle, $this->buildMessage($message, self::LEVEL_DEBUG));
    }

    public function warn(string $message): void
    {
        fwrite($this->fileHandle, $this->buildMessage($message, self::LEVEL_WARN));
    }

    public function error(string $message): void
    {
        fwrite($this->fileHandle, $this->buildMessage($message, self::LEVEL_ERROR));
    }

    private function buildMessage(string $message, string $level): string
    {
        /** @todo php timezone is set to UTC, find a way to make it configurable */
        $timestamp = '['.date('Y-m-d H:i:s', time())."] $level: ";

        return $timestamp.$message.PHP_EOL;
    }

    private function cleanupOldLogs(string $logsDirectory): void
    {
        $cutoff = strtotime('-7 days');

        $logFiles = glob($logsDirectory.'/app-*.log') ?: [];

        foreach ($logFiles as $file) {
            if (preg_match('/app-(\d{4}\d{2}\d{2})\.log$/', $file, $matches)) {
                $logDate = strtotime($matches[1]);

                if ($logDate < $cutoff) {
                    unlink($file);
                }
            }
        }
    }

    public function __destruct()
    {
        // @codeCoverageIgnoreStart
        if ($this->fileHandle !== null) {
            fclose($this->fileHandle);
        }
        // @codeCoverageIgnoreEnd
    }
}
