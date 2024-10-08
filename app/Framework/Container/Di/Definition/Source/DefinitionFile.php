<?php

namespace Framework\Container\Di\Definition\Source;

use Framework\Container\Di\Definition\Definition;

class DefinitionFile extends DefinitionArray
{
    private bool $initialized = false;

    /**
     * @param string $file File in which the definitions are returned as an array.
     */
    public function __construct(
        private string $file,
        ?Autowiring $autowiring = null,
    ) {
        // Lazy-loading to improve performances
        parent::__construct([], $autowiring);
    }

    public function getDefinition(string $name) : Definition|null
    {
        $this->initialize();

        return parent::getDefinition($name);
    }

    public function getDefinitions() : array
    {
        $this->initialize();

        return parent::getDefinitions();
    }

    /**
     * Lazy-loading of the definitions.
     */
    private function initialize() : void
    {
        if ($this->initialized === true) {
            return;
        }

        $definitions = require $this->file;

        if (! is_array($definitions)) {
            throw new \Exception("File $this->file should return an array of definitions");
        }

        $this->addDefinitions($definitions);

        $this->initialized = true;
    }
}