<?php

namespace Bankiru\Doctrine\DiType\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /** {@inheritdoc} */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $root    = $builder->root('doctrine_di_types');

        $root->canBeEnabled();

        $root->children()
             ->scalarNode('init_on_boot')
             ->defaultTrue()
             ->cannotBeEmpty()
             ->info('Forces DI type initialization while bundle boots');

        $root->children()
             ->scalarNode('init_service')
             ->defaultValue('doctrine')
             ->info('Service ID for custom service initialization hook');

        return $builder;
    }
}
