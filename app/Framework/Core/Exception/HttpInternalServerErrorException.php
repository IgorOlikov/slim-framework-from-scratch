<?php

namespace Framework\Core\Exception;

class HttpInternalServerErrorException extends HttpSpecializedException
{
    protected $code = 500;

    /**
     * @var string
     */
    protected $message = 'Internal server error.';

    protected string $title = '500 Internal Server Error';
    protected string $description = 'Unexpected condition encountered preventing server from fulfilling request.';
}