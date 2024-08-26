<?php

namespace Framework\Core\Error;

use Framework\Core\Exception\HttpException;
use Framework\Core\Interfaces\ErrorRendererInterface;
use Throwable;

abstract class AbstractErrorRenderer implements ErrorRendererInterface
{
    protected string $defaultErrorTitle = 'Slim Application Error';

    protected string $defaultErrorDescription = 'A website error has occurred. Sorry for the temporary inconvenience.';

    protected function getErrorTitle(Throwable $exception): string
    {
        if ($exception instanceof HttpException) {
            return $exception->getTitle();
        }

        return $this->defaultErrorTitle;
    }

    protected function getErrorDescription(Throwable $exception): string
    {
        if ($exception instanceof HttpException) {
            return $exception->getDescription();
        }

        return $this->defaultErrorDescription;
    }
}