<?php

namespace Framework\Core\Exception;

class HttpNotImplementedException extends HttpSpecializedException
{
    protected $code = 501;

    /**
     * @var string
     */
    protected $message = 'Not implemented.';

    protected string $title = '501 Not Implemented';
    protected string $description = 'The server does not support the functionality required to fulfill the request.';
}