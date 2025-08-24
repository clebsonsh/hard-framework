<?php

declare(strict_types=1);

namespace App\Handlers\Api;

use App\Dtos\PostRequestDto;
use Infra\Http\Request;
use Infra\Http\Response;
use Infra\Interfaces\RequestHandlerInterface;

class PostHandler implements RequestHandlerInterface
{
    public function handle(Request $request): Response
    {
        return Response::json([
            'data' => $request->getData(PostRequestDto::class),
            'params' => $request->getParams(),
        ]);
    }
}
