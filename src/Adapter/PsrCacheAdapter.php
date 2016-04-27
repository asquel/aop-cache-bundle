<?php
/**
 * aop-cache-bundle
 *
 * Copyright (c) 2012-2013, AsQuel
 * All rights reserved.
 *
 * @since     4/6/16
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
namespace AsQuel\AopCacheBundle\Adapter;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Class PsrCacheAdapter
 *
 * @package   AsQuel\AopCacheBundle\Adapters
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class PsrCacheAdapter implements CacheInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cacheService;

    /**
     * @param CacheItemPoolInterface $cacheService
     */
    public function __construct(CacheItemPoolInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * @param $key
     *
     * @return mixed|false
     */
    public function get($key)
    {
        $item = $this->cacheService->getItem($key);

        if (!$item->isHit()) {
            return false;
        }
        return $item->get();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function contains($key)
    {
        return $this->cacheService->hasItem($key);
    }

    /**
     * @param string $key
     * @param mixed $mixed
     * @param int $ttl
     *
     * @return bool
     */
    public function set($key, $mixed, $ttl)
    {
        $item = $this->cacheService->getItem($key);
        $item->set($mixed);
        $item->expiresAfter($ttl);

        $this->cacheService->save($item);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key)
    {
        return $this->cacheService->deleteItem($key);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return $this->cacheService->clear();
    }
}