<?php
declare(strict_types=1);

namespace App\Decorator;

use App\Integration\DataProviderInterface;
use App\Integration\RequestParams;
use DateTime;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;


/**
 * Class CacheIntegrationProviderDecorator
 *
 * @package App\Decorator
 */
class CacheIntegrationProviderDecorator extends DataProviderDecorator
{
    private const CACHE_PREFIX = 'lessons';
    private const CACHE_EXPIRES = '+1 day';
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * CacheIntegrationProviderDecorator constructor.
     *
     * @param DataProviderInterface  $integrationProvider
     * @param CacheItemPoolInterface $cache
     */
    function __construct(DataProviderInterface $integrationProvider, CacheItemPoolInterface $cache)
    {
        parent::__construct($integrationProvider);
        $this->cache = $cache;
    }

    /**
     * @param RequestParams $requestParams
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function getResponse(RequestParams $requestParams): array
    {
        $cacheKey = self::CACHE_PREFIX.spl_object_hash($requestParams);
        $cacheItem = $this->cache->getItem($cacheKey);
        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $result = parent::getResponse($requestParams);

        $cacheItem
            ->set($result)
            ->expiresAt(
                (new DateTime())->modify(self::CACHE_EXPIRES)
            );

        $this->cache->save($cacheItem);

        return $result;
    }
}
