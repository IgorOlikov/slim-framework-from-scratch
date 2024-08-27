<?php

namespace Framework\Container\Di\Definition;

use Framework\Container\Di\Definition\Exception\InvalidDefinition;

class ArrayDefinitionExtension extends ArrayDefinition implements ExtendsPreviousDefinition
{
    private ?ArrayDefinition $subDefinition = null;

    public function getValues() : array
    {
        if (! $this->subDefinition) {
            return parent::getValues();
        }

        return array_merge($this->subDefinition->getValues(), parent::getValues());
    }

    public function setExtendedDefinition(Definition $definition) : void
    {
        if (! $definition instanceof ArrayDefinition) {
            throw new InvalidDefinition(sprintf(
                'Definition %s tries to add array entries but the previous definition is not an array',
                $this->getName()
            ));
        }

        $this->subDefinition = $definition;
    }
}