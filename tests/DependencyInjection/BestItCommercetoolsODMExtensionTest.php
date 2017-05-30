<?php

namespace BestIt\CommercetoolsODMBundle\Tests\DependencyInjection;

use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderComposite;
use BestIt\CommercetoolsODM\ActionBuilder\ActionBuilderFactory;
use BestIt\CommercetoolsODM\DocumentManager;
use BestIt\CommercetoolsODM\Event\ListenersInvoker;
use BestIt\CommercetoolsODM\Filter\FilterManager;
use BestIt\CommercetoolsODM\Mapping\ClassMetadataFactory;
use BestIt\CommercetoolsODM\Mapping\Driver\AnnotationDriver;
use BestIt\CommercetoolsODM\RepositoryFactory;
use BestIt\CommercetoolsODM\UnitOfWorkFactory;
use BestIt\CommercetoolsODMBundle\DependencyInjection\BestItCommercetoolsODMExtension;
use Commercetools\Commons\Helper\QueryHelper;
use Doctrine\Common\EventManager;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Psr\Log\NullLogger;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class BestItCommercetoolsODMExtensionTest
 * @author blange <lange@bestit-online.de>
 * @category Tests
 * @package BestIt\CommercetoolsODMBundle
 * @subpackage DependencyInjection
 * @version $id$
 */
class BestItCommercetoolsODMExtensionTest extends AbstractExtensionTestCase
{
    /**
     * Return an array of container extensions you need to be registered for each test (usually just the container
     * extension you are testing.
     *
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions(): array
    {
        return [new BestItCommercetoolsODMExtension()];
    }

    /**
     * Returns assertions for checking declared services.
     * @return array
     */
    public function getDeclaredServices(): array
    {
        return [
            // Service id, optional class name
            ['best_it.commercetools_odm.action_builder.factory', ActionBuilderFactory::class],
            ['best_it.commercetools_odm.action_builder.factory.default', ActionBuilderFactory::class],
            ['best_it.commercetools_odm.action_builder.processor', ActionBuilderComposite::class],
            ['best_it.commercetools_odm.action_builder.processor.default', ActionBuilderComposite::class],
            ['best_it.commercetools_odm.class_metadata_factory', ClassMetadataFactory::class],
            ['best_it.commercetools_odm.class_metadata_factory.default', ClassMetadataFactory::class],
            ['best_it.commercetools_odm.event_manager', EventManager::class],
            ['best_it.commercetools_odm.event_manager.default', EventManager::class],
            ['best_it.commercetools_odm.listener_invoker', ListenersInvoker::class],
            ['best_it.commercetools_odm.listener_invoker.default', ListenersInvoker::class],
            ['best_it.commercetools_odm.manager', DocumentManager::class],
            ['best_it.commercetools_odm.mapping.annotations.driver', AnnotationDriver::class],
            ['best_it.commercetools_odm.mapping.annotations.driver.default', AnnotationDriver::class],
            ['best_it.commercetools_odm.query_helper', QueryHelper::class],
            ['best_it.commercetools_odm.query_helper.default', QueryHelper::class],
            ['best_it.commercetools_odm.repository_factory', RepositoryFactory::class],
            ['best_it.commercetools_odm.repository_factory.default', RepositoryFactory::class],
            ['best_it.commercetools_odm.unit_of_work.factory', UnitOfWorkFactory::class],
            ['best_it.commrecetools_odm.unit_of_work.factory.default', UnitOfWorkFactory::class],
            ['best_it.commercetools_odm.filter.filter_manager', FilterManager::class]
        ];
    }

    /**
     * Returns the minimal configuration.
     * @return array
     */
    protected function getMinimalConfiguration(): array
    {
        return [
            'client_service_id' => uniqid()
        ];
    }

    /**
     * Fakes a logger service.
     * @return void
     */
    private function mockLoggerService()
    {
        $this->registerService('app.logger', NullLogger::class);

        $this->load(['logger_service_id' => 'app.logger']);

        $this->assertContainerBuilderHasAlias(
            'best_it.commercetools_odm.logger', 'app.logger'
        );

        $this->assertContainerBuilderHasService('app.logger', NullLogger::class);
    }

    /**
     * Sets up the test.
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->load();
    }

    /**
     * Checks if a declared service exists.
     * @dataProvider getDeclaredServices
     * @param string $serviceId
     * @param string $serviceClass
     * @param string $tag
     */
    public function testDeclaredServices(string $serviceId, string $serviceClass = '', string $tag = '')
    {
        $this->assertContainerBuilderHasService($serviceId, $serviceClass ?: null);

        if ($tag) {
            $this->assertContainerBuilderHasServiceDefinitionWithTag($serviceId, $tag);
        }
    }

    /**
     * Checks if there is no logger alias.
     * @return void
     */
    public function testLoggerAliasNoConfig()
    {
        $this->assertContainerBuilderNotHasService('best_it.commercetools_odm.logger');
    }

    /**
     * Checks if there is an logger alias.
     * @return void
     */
    public function testLoggerAliasWithConfig()
    {
        $this->mockLoggerService();
    }

    /**
     * Checks if the service is correctly loaded and registered, even with a logger.
     * @return void
     */
    public function testDocumentManagerServiceWithLoggerConfig()
    {
        $this->mockLoggerService();

        $this->assertContainerBuilderHasService('best_it.commercetools_odm.manager', DocumentManager::class);
    }

    /**
     * Checks if the service is correctly loaded and registered, even with a logger.
     * @return void
     */
    public function testUnitOfWorkFactoryServiceWithLoggerConfig()
    {
        $this->mockLoggerService();

        $this->assertContainerBuilderHasService(
            'best_it.commercetools_odm.unit_of_work.factory',
            UnitOfWorkFactory::class
        );
    }
}
