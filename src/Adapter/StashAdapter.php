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

use Stash\Interfaces\PoolInterface;

/**
 * Class StashAdapter
 *
 * @package   AsQuel\AopCacheBundle\Adapters
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class StashAdapter implements CacheInterface
{
    /**
     * @var PoolInterface
     */
    private $cacheService;

    /**
     * @param PoolInterface $cacheService
     */
    public function __construct(PoolInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        $item = $this->cacheService->getItem($key);

        if ($item->isMiss()) {
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
        return !$this->cacheService->getItem($key)->isMiss();
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
        return $item->set($mixed, $ttl);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key)
    {
        $item = $this->cacheService->getItem($key);

        return $item->clear();
    }

    /**
     * @return bool
     */
    public function clear()
    {
        return $this->cacheService->flush();
    }
}