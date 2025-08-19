<?php

declare(strict_types=1);

use App\Handlers\Api\PostHandler;
use App\Handlers\Api\TestHandler;
use Infra\Http\Router;

Router::get('/api/test', new TestHandler);
Router::post('/api/post', new PostHandler);
