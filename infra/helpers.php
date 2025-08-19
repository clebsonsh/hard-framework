<?php

declare(strict_types=1);

use Infra\Http\Response;

define('ROOT_PATH', dirname(__DIR__));

function public_path(): string
{
    return realpath(ROOT_PATH).'/public/';
}
function views_path(): string
{
    return realpath(ROOT_PATH).'/app/Views/';
}

/** @param array<string, string> $data */
function view(string $path, array $data = []): Response
{
    $filePath = views_path().$path.'.html';

    if (! file_exists($filePath)) {
        throw new RuntimeException("View $path not found");
    }

    $template = (string) file_get_contents($filePath);

    foreach ($data as $key => $value) {
        $template = str_replace('{{'.$key.'}}', htmlspecialchars($value), $template);
    }

    return Response::html($template);
}
