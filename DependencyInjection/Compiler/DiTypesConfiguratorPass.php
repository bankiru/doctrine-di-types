<?php

namespace Bankiru\Doctrine\DiType\DependencyInjection\Compiler;

use Bankiru\Doctrine\DiType\DependencyInjection\DummyConfiguratorWrapper;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class DiTypesConfiguratorPass implements CompilerPassInterface
{
    /** {@inheritdoc} */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('bankiru_doctrine_di_types.type_factory')) {
            return;
        }

        $configurator = $container->register(
            'bankiru_doctrine_di_types.configurator_wrapper',
            DummyConfiguratorWrapper::class
        );

        $service = $container->getParameterBag()->resolveValue(
            $container->getParameter('bankiru_doctrine_di_types.init_service')
        );

        $this->configureConfiguratorFor($container, $configurator, $service);

        $factory  = $container->getDefinition('bankiru_doctrine_di_types.type_factory');
        $services = $container->findTaggedServiceIds('doctrine_di_type');

        foreach ($services as $id => $tags) {
            $typeDef = $container->getDefinition($id);
            foreach ($tags as $attributes) {
                if (!array_key_exists('type', $attributes)) {
                    throw new \LogicException(
                        'You must specify "type" attribute in order to use "doctrine_di_type" tag'
                    );
                }
                $typeDef->setFactory([new Reference('bankiru_doctrine_di_types.type_factory'), 'create']);
                $typeDef->setConfigurator([new Reference('bankiru_doctrine_di_types.type_configurator'), 'configure']);

                $typeDef->setArguments([$attributes['type']]);
                $factory->addMethodCall('addType', [$attributes['type'], $typeDef->getClass()]);
                $configurator->addMethodCall('addType', [new Reference($id)]);
            }
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param Definition       $configurator
     * @param string           $id
     */
    private function configureConfiguratorFor(ContainerBuilder $container, Definition $configurator, $id)
    {
        $service = $container->getDefinition($id);

        $configurator->setArguments([$service->getConfigurator()]);

        $service->setConfigurator([$configurator, 'configure']);
    }
}
