<?php

namespace BestIt\CommercetoolsODMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add filters to manager
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle\DependencyInjection\Compiler
 */
class FilterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($service = 'best_it.commercetools_odm.filter.filter_manager')) {
            return;
        }

        $definition = $container->findDefinition($service);
        foreach ($container->findTaggedServiceIds('best_it_commercetools_odm.filter') as $id => $tags) {
            $definition->addMethodCall('add', [new Reference($id)]);
        }
    }
}
