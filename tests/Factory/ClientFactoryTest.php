<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODMBundle\Tests\DependencyInjection;

use BestIt\CommercetoolsODMBundle\Factory\ClientFactory;
use CommerceTools\Core\Client;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\ErrorHandler\BufferingLogger;

/**
 * Test the client factory
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle\Tests\DependencyInjection
 */
class ClientFactoryTest extends TestCase
{

    /**
     * Test the create method
     *
     * @return void
     */
    public function testCreate()
    {
        $factory = new ClientFactory(
            $cache = $this->createMock(CacheItemPoolInterface::class),
            $logger = new BufferingLogger()
        );

        $client = $factory->create(
            [
                'client_id' => uniqid(),
                'client_secret' => uniqid(),
                'project' => uniqid(),
                'scope' => ['manage_project']
            ],
            [
                'locale' => 'de',
                'languages' => ['de']
            ]
        );

        static::assertInstanceOf(Client::class, $client);
    }
}
