<?php

namespace Framework\Core\Error\Renderers;

use Framework\Core\Error\AbstractErrorRenderer;
use Throwable;

class XmlErrorRenderer extends AbstractErrorRenderer
{
    public function __invoke(Throwable $exception, bool $displayErrorDetails): string
    {
        $xml = '<' . '?xml version="1.0" encoding="UTF-8" standalone="yes"?' . ">\n";
        $xml .= "<error>\n  <message>" . $this->createCdataSection($this->getErrorTitle($exception)) . "</message>\n";

        if ($displayErrorDetails) {
            do {
                $xml .= "  <exception>\n";
                $xml .= '    <type>' . get_class($exception) . "</type>\n";
                $xml .= '    <code>' . $exception->getCode() . "</code>\n";
                $xml .= '    <message>' . $this->createCdataSection($exception->getMessage()) . "</message>\n";
                $xml .= '    <file>' . $exception->getFile() . "</file>\n";
                $xml .= '    <line>' . $exception->getLine() . "</line>\n";
                $xml .= "  </exception>\n";
            } while ($exception = $exception->getPrevious());
        }

        $xml .= '</error>';

        return $xml;
    }

    /**
     * Returns a CDATA section with the given content.
     */
    private function createCdataSection(string $content): string
    {
        return sprintf('<![CDATA[%s]]>', str_replace(']]>', ']]]]><![CDATA[>', $content));
    }
}