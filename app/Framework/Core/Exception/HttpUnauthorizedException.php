<?php

namespace Framework\Core\Exception;

class HttpUnauthorizedException extends HttpSpecializedException
{
    protected $code = 401;

    /**
     * @var string
     */
    protected $message = 'Unauthorized.';

    protected string $title = '401 Unauthorized';
    protected string $description = 'The request requires valid user authentication.';
}