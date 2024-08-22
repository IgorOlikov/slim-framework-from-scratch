<?php

namespace Framework\Psr7Request\Factory;

use Framework\Fig\StatusCodeInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Psr\Http\Message\ResponseInterface;
use Override;

class ResponseFactory implements ResponseFactoryInterface
{

    #[Override] public function createResponse(int $code = StatusCodeInterface::STATUS_OK, string $reasonPhrase = ''): ResponseInterface
    {
        $res = new Response($code);

        if ($reasonPhrase !== '') {
            $res = $res->withStatus($code, $reasonPhrase);
        }

    }
}