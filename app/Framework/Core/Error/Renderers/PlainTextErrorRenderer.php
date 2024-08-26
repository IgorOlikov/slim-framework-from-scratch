<?php

namespace Framework\Core\Error\Renderers;

use Framework\Core\Error\AbstractErrorRenderer;
use Throwable;

class PlainTextErrorRenderer extends AbstractErrorRenderer
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $text = "{$this->getErrorTitle($exception)}\n";

        if ($displayErrorDetails) {
            $text .= $this->formatExceptionFragment($exception);

            while ($exception = $exception->getPrevious()) {
                $text .= "\nPrevious Error:\n";
                $text .= $this->formatExceptionFragment($exception);
            }
        }

        return $text;
    }

    private function formatExceptionFragment(Throwable $exception): string
    {
        $text = sprintf("Type: %s\n", get_class($exception));

        $code = $exception->getCode();

        $text .= sprintf("Code: %s\n", $code);

        $text .= sprintf("Message: %s\n", $exception->getMessage());

        $text .= sprintf("File: %s\n", $exception->getFile());

        $text .= sprintf("Line: %s\n", $exception->getLine());

        $text .= sprintf('Trace: %s', $exception->getTraceAsString());

        return $text;
    }
}