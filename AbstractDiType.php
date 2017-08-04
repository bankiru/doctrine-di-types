<?php

namespace Bankiru\Doctrine\DiType;

use Doctrine\DBAL\Types\Type;

abstract class AbstractDiType extends Type implements DiTypeInterface
{
    /** @var static */
    private $original;

    public function configure(DiTypeInterface $original)
    {
        $this->original = $original;
    }

    /**
     * @return static
     */
    final protected function getOriginal()
    {
        return $this->original;
    }
}
