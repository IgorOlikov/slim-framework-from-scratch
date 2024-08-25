<?php

namespace Framework\Core\Exception;

class HttpGoneException extends HttpSpecializedException
{
    protected $code = 410;

    /**
     * @var string
     */
    protected $message = 'Gone.';

    protected string $title = '410 Gone';
    protected string $description = 'The target resource is no longer available at the origin server.';
}