<?php

namespace Framework\Core\Exception;

use Framework\Psr\Http\Message\ServerRequestInterface;
use Throwable;

class HttpSpecializedException extends HttpException
{
    public function __construct(ServerRequestInterface $request, ?string $message = null, ?Throwable $previous = null)
    {
        if ($message !== null) {
            $this->message = $message;
        }

        parent::__construct($request, $this->message, $this->code, $previous);
    }
}