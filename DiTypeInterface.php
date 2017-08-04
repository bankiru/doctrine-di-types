<?php

namespace Bankiru\Doctrine\DiType;

interface DiTypeInterface
{
    /**
     * Type configurator
     *
     * The same entity, obtained from Symfony DI will be passed here.
     * You can fetch any additional configuration from it
     *
     * @param static $original
     *
     * @return mixed
     */
    public function configure(DiTypeInterface $original);
}
