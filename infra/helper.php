<?php

declare(strict_types=1);

use Infra\Support\Log;

function root_path(): string
{
    return dirname(__DIR__);
}

function storage_path(): string
{
    return root_path().'/storage';
}

function info(string $message): void
{
    $logger = Log::getInstance();

    $logger->info($message);
}

function debug(string $message): void
{
    $logger = Log::getInstance();

    $logger->debug($message);
}
function error(string $message): void
{
    $logger = Log::getInstance();

    $logger->error($message);
}
function warn(string $message): void
{
    $logger = Log::getInstance();

    $logger->warn($message);
}
