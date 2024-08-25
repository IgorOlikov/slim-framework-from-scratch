<?php

namespace Framework\Core\Exception;

class HttpBadRequestException extends HttpSpecializedException
{
    protected $code = 400;

    /**
     * @var string
     */
    protected $message = 'Bad request.';

    protected string $title = '400 Bad Request';
    protected string $description = 'The server cannot or will not process ' .
    'the request due to an apparent client error.';
}