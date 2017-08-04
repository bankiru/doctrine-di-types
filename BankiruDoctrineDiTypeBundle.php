<?php

namespace Bankiru\Doctrine\DiType;

use Bankiru\Doctrine\DiType\DependencyInjection\BankiruDoctrineDiTypeExtension;
use Bankiru\Doctrine\DiType\DependencyInjection\Compiler\DiTypesConfiguratorPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BankiruDoctrineDiTypeBundle extends Bundle
{
    public function boot()
    {
        parent::boot();

        if (!$this->container->has('bankiru_doctrine_di_types.type_factory')) {
            return;
        }

        if ($this->container->getParameter('bankiru_doctrine_di_types.force_fetch_on_boot')) {
            $this->container->get(
                $this->container->getParameter('bankiru_doctrine_di_types.init_service')
            );
        }
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DiTypesConfiguratorPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }

    public function getContainerExtension()
    {
        return new BankiruDoctrineDiTypeExtension();
    }
}
