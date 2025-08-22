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

