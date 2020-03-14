<?php

namespace BestIt\CommercetoolsODMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class for this bundle.
 *
 * @author Bjoern Lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Parses the config.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder('bestit_commercetools_odm');
        $rootNode = $this->getRootNode($builder, 'bestit_commercetools_odm');

        $rootNode
            ->children()
                ->scalarNode('client_service_id')
                    ->info('Please provide the service id for your commercetools client.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('logger_service_id')
                    ->info('Please provider the service id for your preferred logger.')
                ->end()
                ->scalarNode('pool_service_id')
                    ->info('Please provide the service id for your commercetools request async pool.')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $builder;
    }

    /**
     * BC layer for symfony/config 4.1 and older
     *
     * @param TreeBuilder $treeBuilder
     * @param string $name
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    private function getRootNode(TreeBuilder $treeBuilder, string $name)
    {
        if (!method_exists($treeBuilder, 'getRootNode')) {
            return $treeBuilder->root($name);
        }

        return $treeBuilder->getRootNode();
    }
}
