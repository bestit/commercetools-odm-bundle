<?php

namespace BestIt\CommercetoolsODMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class for this bundle.
 * @author Bjoern Lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle
 * @subpackage DependencyInjection
 * @version $id$
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Parses the config.
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $rootNode = $builder->root('bestit_commercetools_odm');

        $rootNode
            ->children()
                ->scalarNode('client_service_id')
                    ->info('Please provide the service id for your commercetools client.')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('pool_service_id')
                    ->info('Please provide the service id for your commercetools request async pool.')
                    ->cannotBeEmpty()
                ->end()
            ->end();

        return $builder;
    }
}
