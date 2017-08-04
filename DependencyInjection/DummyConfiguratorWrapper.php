<?php

namespace Bankiru\Doctrine\DiType\DependencyInjection;

use Bankiru\Doctrine\DiType\DiTypeFactory;
use Bankiru\Doctrine\DiType\DiTypeInterface;

final class DummyConfiguratorWrapper
{
    /** @var callable|null */
    private $previous;
    /** @var DiTypeFactory */
    private $types = [];

    /**
     * DummyConfiguratorWrapper constructor.
     *
     * @param callable|null $previous
     */
    public function __construct(callable $previous = null)
    {
        $this->previous = $previous;
    }

    public function addType(DiTypeInterface $type)
    {
        $this->types[] = $type;
    }

    public function configure($something)
    {
        if (is_callable($this->previous)) {
            call_user_func($this->previous, $something);
        }
    }
}

