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

/**
 * Interface CacheInterface
 *
 * @package   AsQuel\AopCacheBundle\Adapters
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
interface CacheInterface
{
    /**
     * @param mixed $key The id of the cache entry to fetch.
     *
     * @return mixed The cached data or FALSE, if no cache entry exists for the given id.
     */
    public function get($key);

    /**
     * @param mixed $key The id of the cache entry to check for.
     *
     * @return bool
     */
    public function contains($key);

    /**
     * @param mixed $key
     * @param mixed $mixed
     * @param int   $ttl
     *
     * @return bool TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    public function set($key, $mixed, $ttl);

    /**
     * @param mixed $key
     *
     * @return bool TRUE if the entry was successfully deleted from the cache, FALSE otherwise.
     */
    public function delete($key);

    /**
     * @return mixed
     */
    public function clear();
}