<?php
/**
 * aop-cache-bundle
 *
 * Copyright (c) 2012-2013, AsQuel
 * All rights reserved.
 *
 * @since     2/5/16
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
namespace AsQuel\AopCacheBundle\Tests\Adapter;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AsQuel\AopCacheBundle\Adapter\DoctrineCacheAdapter;

/**
 * Class DoctrineCacheAdapterTest
 *
 * @package   AsQuel\AopCacheBundle\Tests\Adapter
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class DoctrineCacheAdapterTest extends WebTestCase
{

    public function testInterface()
    {
        $client  = static::createClient();
        $adapter = new DoctrineCacheAdapter(
            $client->getContainer()->get('doctrine_cache.providers.arrayDoctrineCache')
        );
        $this->assertInstanceOf('AsQuel\AopCacheBundle\Adapter\CacheInterface', $adapter);

        return $adapter;
    }

    /**
     * @depends testInterface
     */
    public function testSet($adapter)
    {
        $val = 'XXX';
        $res = $adapter->set('key1', $val, 2);

        $this->assertTrue($res);

        return $adapter;
    }

    /**
     * @depends testSet
     */
    public function testGet($adapter)
    {
        $res = $adapter->get('key1');

        $this->assertEquals('XXX', $res);

        return $adapter;
    }

    /**
     * @depends testGet
     */
    public function testDelete($adapter)
    {
        $res = $adapter->delete('key1');

        $this->assertTrue($res);
        $res = $adapter->get('key1');
        $this->assertFalse($res);
    }

    /**
     * @depends testGet
     */
    public function testClear($adapter)
    {
        $res = $adapter->clear();

        $this->assertTrue($res);
    }
}