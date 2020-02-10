<?php

declare(strict_types=1);

namespace BestIt\CommercetoolsODMBundle\Factory;

use Commercetools\Core\Client;
use Commercetools\Core\Config;
use Commercetools\Core\Model\Common\Context;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

/**
 * Factory for commercetools client
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package BestIt\CommercetoolsODMBundle\Factory
 */
class ClientFactory
{
    /**
     * The psr logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * The cache
     *
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * ClientFactory constructor.
     *
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->cache = $cache;
    }

    /**
     * Create commercetools client instance
     *
     * @param array $commerceToolsCredentials
     * @param array $commerceToolsContext
     *
     * @return Client
     */
    public function create(array $commerceToolsCredentials, array $commerceToolsContext): Client
    {
        $context = new Context();
        foreach ($commerceToolsContext as $key => $value) {
            $context[$key] = $value;
        }

        $config = Config::fromArray($commerceToolsCredentials);
        $config->setContext($context);

        return Client::ofConfigCacheAndLogger($config, $this->cache, $this->logger);
    }
}
