<?php

namespace Bankiru\Doctrine\DiType;

use Doctrine\DBAL\Types\Type;

final class DiTypeFactory
{
    private $classes = [];

    public function addType($type, $class)
    {
        $this->classes[$type] = $class;
    }

    public function create($type)
    {
        if (!array_key_exists($type, $this->classes)) {
            throw new \OutOfBoundsException(sprintf('Type "%s" is not registered it DI Type Factory', $type));
        }

        if (Type::hasType($type)) {
            Type::overrideType($type, $this->classes[$type]);
        } else {
            Type::addType($type, $this->classes[$type]);
        }

        return Type::getType($type);
    }
}
