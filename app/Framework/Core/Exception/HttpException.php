<?php

namespace Framework\Core\Exception;

use Framework\Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Throwable;

class HttpException extends RuntimeException
{
    protected ServerRequestInterface $request;

    protected string $title = '';

    protected string $description = '';

    public function __construct(
        ServerRequestInterface $request,
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }
}