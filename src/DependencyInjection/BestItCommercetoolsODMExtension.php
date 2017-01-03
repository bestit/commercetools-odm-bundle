<?php

namespace BestIt\CommercetoolsODMBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads the config for the commercetools odm bundle.
 * @author Bjoern Lange <lange@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle
 * @subpackage DependencyInjection
 * @version $id$
 */
class BestItCommercetoolsODMExtension extends Extension
{
    /**
     * Loads the bundle config.
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setAlias('best_it.commercetools_odm.client', $config['client_service_id']);

        $aliasMap = [
            'best_it.commercetools_odm.event_manager' => 'best_it.commercetools_odm.event_manager.default',
            'best_it.commercetools_odm.listener_invoker' => 'best_it.commercetools_odm.listener_invoker.default',
            'best_it.commercetools_odm.query_helper' => 'best_it.commercetools_odm.query_helper.default',
            'best_it.commercetools_odm.repository_factory' => 'best_it.commercetools_odm.repository_factory.default',
            'best_it.commercetools_odm.action_builder.factory' => 'best_it.commercetools_odm.action_builder.' .
                'factory.default',
            'best_it.commercetools_odm.action_builder.processor' => 'best_it.commercetools_odm.action_builder.' .
                'processor.default',
            'best_it.commercetools_odm.class_metadata_factory' =>
                'best_it.commercetools_odm.class_metadata_factory.default',
            'best_it.commercetools_odm.mapping.annotations.driver' =>
                'best_it.commercetools_odm.mapping.annotations.driver.default',
            'best_it.commercetools_odm.unit_of_work.factory' =>
                'best_it.commrecetools_odm.unit_of_work.factory.default',
        ];

        array_walk($aliasMap, function (string $original, string $alias) use ($container) {
            $container->setAlias($alias, $original);
        });
    }
}
