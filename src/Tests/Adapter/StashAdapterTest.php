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
use AsQuel\AopCacheBundle\Adapter\StashAdapter;

/**
 * Class StashAdapterTest
 *
 * @package   AsQuel\AopCacheBundle\Tests\Adapter
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class StashAdapterTest extends WebTestCase
{
    /**
     * @return StashAdapter
     */
    public function testInterface()
    {
        $client  = static::createClient();

        $adapter = new StashAdapter(
            $client->getContainer()->get('stash')
        );
        $this->assertInstanceOf('AsQuel\AopCacheBundle\Adapter\CacheInterface', $adapter);

        return $adapter;
    }

    /**
     * @depends testInterface
     *
     * @param StashAdapter $adapter
     *
     * @return StashAdapter
     */
    public function testSet(StashAdapter $adapter)
    {
        $val = 'XXX';
        $res = $adapter->set('key1', $val, 2);

        $this->assertTrue($res);

        return $adapter;
    }

    /**
     * @depends testSet
     *
     * @param StashAdapter $adapter
     *
     * @return StashAdapter
     */
    public function testGet(StashAdapter $adapter)
    {
        $res = $adapter->get('key1');

        $this->assertEquals('XXX', $res);

        return $adapter;
    }

    /**
     * @depends testGet
     *
     * @param StashAdapter $adapter
     */
    public function testDelete(StashAdapter $adapter)
    {
        $res = $adapter->delete('key1');

        $this->assertTrue($res);
        $res = $adapter->get('key1');
        $this->assertFalse($res);
    }

    /**
     * @depends testGet
     *
     * @param StashAdapter $adapter
     */
    public function testClear(StashAdapter $adapter)
    {
        $res = $adapter->clear();

        $this->assertFalse($res);
    }
}