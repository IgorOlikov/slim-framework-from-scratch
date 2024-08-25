<?php

namespace Framework\Core\Exception;

class HttpForbiddenException extends HttpSpecializedException
{
    protected $code = 403;

    /**
     * @var string
     */
    protected $message = 'Forbidden.';

    protected string $title = '403 Forbidden';
    protected string $description = 'You are not permitted to perform the requested operation.';
}