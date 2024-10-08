<?php

namespace Framework\Container\Di\Definition;

class EnvironmentVariableDefinition implements Definition
{
    /** Entry name. */
    private string $name = '';

    /**
     * @param string $variableName The name of the environment variable
     * @param bool $isOptional Whether or not the environment variable definition is optional. If true and the environment variable given by $variableName has not been defined, $defaultValue is used.
     * @param mixed $defaultValue The default value to use if the environment variable is optional and not provided
     */
    public function __construct(
        private string $variableName,
        private bool $isOptional = false,
        private mixed $defaultValue = null,
    ) {
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setName(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * @return string The name of the environment variable
     */
    public function getVariableName() : string
    {
        return $this->variableName;
    }

    /**
     * @return bool Whether or not the environment variable definition is optional
     */
    public function isOptional() : bool
    {
        return $this->isOptional;
    }

    /**
     * @return mixed The default value to use if the environment variable is optional and not provided
     */
    public function getDefaultValue() : mixed
    {
        return $this->defaultValue;
    }

    public function replaceNestedDefinitions(callable $replacer) : void
    {
        $this->defaultValue = $replacer($this->defaultValue);
    }

    public function __toString() : string
    {
        $str = '    variable = ' . $this->variableName . \PHP_EOL
            . '    optional = ' . ($this->isOptional ? 'yes' : 'no');

        if ($this->isOptional) {
            if ($this->defaultValue instanceof Definition) {
                $nestedDefinition = (string) $this->defaultValue;
                $defaultValueStr = str_replace(\PHP_EOL, \PHP_EOL . '    ', $nestedDefinition);
            } else {
                $defaultValueStr = var_export($this->defaultValue, true);
            }

            $str .= \PHP_EOL . '    default = ' . $defaultValueStr;
        }

        return sprintf('Environment variable (' . \PHP_EOL . '%s' . \PHP_EOL . ')', $str);
    }
}