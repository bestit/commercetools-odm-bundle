<?php

namespace BestIt\CommercetoolsODMBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds the events to the event manager.
 * @author blane <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle
 * @subpackage DependencyInjection\Compiler
 * @version $id$
 */
class EventListenerPass implements CompilerPassInterface
{
    /**
     * Adds the listeners.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->has($service = 'best_it.commercetools_odm.event_manager')) {
            $definition = $container->findDefinition($service);

            foreach ($container->findTaggedServiceIds('best_it_commercetools_odm.event_listener') as $id => $tags) {
                foreach ($tags as $tag) {
                    if (@$tag['event']) {
                        $definition->addMethodCall('addEventListener', [$tag['event'], new Reference($id)]);
                        break;
                    }
                }
            }
        }
    }
}
