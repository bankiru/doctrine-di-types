<?php

namespace Bankiru\Doctrine\DiType\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class BankiruDoctrineDiTypeExtension extends Extension
{
    /** {@inheritdoc} */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        if (!$this->isConfigEnabled($container, $config)) {
            return;
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/'));
        $loader->load('services.yml');

        $container->setParameter('bankiru_doctrine_di_types.force_fetch_on_boot', $config['init_on_boot']);
        $container->setParameter('bankiru_doctrine_di_types.init_service', $config['init_service']);
    }

    public function getAlias()
    {
        return 'doctrine_di_types';
    }
}
