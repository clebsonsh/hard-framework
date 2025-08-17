<?php

declare(strict_types=1);

use App\Infra\Request;

return [
    '/' => function (Request $request) {
        echo $request->test;
    },
    '/about' => fn () => print 'about',
    '/404' => fn () => print 'not found',
];

