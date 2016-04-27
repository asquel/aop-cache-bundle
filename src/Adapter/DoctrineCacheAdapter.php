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

use Doctrine\Common\Cache\Cache as DoctrineCacheInterface;

/**
 * Class DoctrineCacheAdapter
 *
 * @package   AsQuel\AopCacheBundle\Adapters
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class DoctrineCacheAdapter implements CacheInterface
{
    /**
     * @var DoctrineCacheInterface
     */
    private $cacheService;

    /**
     * @param DoctrineCacheInterface $cacheService
     */
    public function __construct(DoctrineCacheInterface $cacheService)
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
        return $this->cacheService->fetch($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function contains($key)
    {
        return $this->cacheService->contains($key);
    }

    /**
     * @param $key
     * @param $mixed
     * @param $ttl
     *
     * @return bool
     */
    public function set($key, $mixed, $ttl)
    {
        return $this->cacheService->save($key, $mixed, $ttl);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function delete($key)
    {
        return $this->cacheService->delete($key);
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return $this->cacheService->deleteAll();
    }

}