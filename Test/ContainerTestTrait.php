<?php

namespace Bankiru\Doctrine\DiType\Test;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

trait ContainerTestTrait
{
    /**
     * @param BundleInterface[] $bundles
     * @param array             $configs
     * @param bool              $compile
     *
     * @return ContainerBuilder
     */
    protected function buildContainer(array $bundles = [], array $configs = [], $compile = true)
    {
        $container = new ContainerBuilder(
            new ParameterBag(
                [
                    'kernel.debug'       => false,
                    'kernel.bundles'     => $bundles,
                    'kernel.cache_dir'   => sys_get_temp_dir(),
                    'kernel.environment' => 'test',
                    'kernel.root_dir'    => __DIR__,
                ]
            )
        );
        foreach ($bundles as $bundle) {
            $bundle->build($container);
            $this->loadExtension($bundle, $container, $configs);
        }

        if ($compile) {
            $this->compile($container);
        }

        return $container;
    }

    /**
     * @param BundleInterface  $bundle
     * @param ContainerBuilder $container
     * @param array            $configs
     */
    protected function loadExtension(BundleInterface $bundle, ContainerBuilder $container, array $configs)
    {
        $extension = $bundle->getContainerExtension();
        if (!$extension) {
            return;
        }

        $config = [];

        if (array_key_exists($extension->getAlias(), $configs)) {
            $config = [$configs[$extension->getAlias()]];
        }

        $extension->load($config, $container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function compile(ContainerBuilder $container)
    {
        $container->compile();

        // Check that container can be dumped
        $dumper = new PhpDumper($container);
        $dumper->dump();

        $this->boot($container);
    }

    protected function boot(ContainerBuilder $container)
    {
        /** @var BundleInterface[] $bundles */
        $bundles = $container->getParameter('kernel.bundles');
        foreach ($bundles as $bundle) {
            $bundle->setContainer($container);
            $bundle->boot();
        }
    }
}
