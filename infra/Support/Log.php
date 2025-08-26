<?php

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
            mkdir(
                directory: $logsDirectory,
                permissions: 0755,
                recursive: true,
            );
        }

        $today = date('Ymd');
        $this->filePath = $logsDirectory."/app-{$today}.log";

        /** @todo delete old log files */
        $fileHandle = fopen($this->filePath, 'a');

        if (! $fileHandle) {
            throw new RuntimeException("Unable to open log file: $this->filePath");
        }

        $this->fileHandle = $fileHandle;
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

    public function __destruct()
    {
        if ($this->fileHandle !== null) {
            fclose($this->fileHandle);
        }
    }
}
