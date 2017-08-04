<?php

namespace Bankiru\Doctrine\DiType\DependencyInjection;

use Bankiru\Doctrine\DiType\DiTypeInterface;

final class DiTypeConfigurator
{
    public function configure(DiTypeInterface $type)
    {
        $type->configure($type);
    }
}
