<?php

namespace Src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Src\Integration\DataProviderInterface;

class DecoratorManager implements ResponseManagerInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var DataProviderInterface
     */
    private $dataProvider;


    /**
     * DecoratorManager constructor.
     *
     * @param DataProviderInterface $dataProvider
     * @param CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(DataProviderInterface $dataProvider, CacheItemPoolInterface $cache, LoggerInterface $logger)
    {
        $this->dataProvider = $dataProvider;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input)
    {
        $cacheKey = $this->getCacheKey($input);

        try {
            $cacheItem = $this->cache->getItem($cacheKey);
            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = $this->dataProvider->get($input);

            $cacheItem
                ->set($result)
                ->expiresAt(
                    (new DateTime())->modify('+1 day')
                );

            return $result;
        } catch (InvalidArgumentException $e) {
            $this->logger->critical("$cacheKey string is not a legal value for retrieve Cache Item");
        } catch (Exception $e) {
            $this->logger->critical('DecoratorManager.getResponse() error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey(array $input)
    {
        return json_encode($input);
    }
}

