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
namespace AsQuel\AopCacheBundle\Tests\DependencyInjection;

use Go\Core\GoAspectContainer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class TrivagoAopCacheExtensionTest
 *
 * @package   AsQuel\AopCacheBundle\Tests\DependencyInjection
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class TrivagoAopCacheExtensionTest extends WebTestCase
{

    public function testServices()
    {
        $client    = static::createClient();

        $aspect = $client->getContainer()->get('trivago.aspect.cacheable');
        $this->assertInstanceOf('AsQuel\AopCacheBundle\Service\CachingAspect', $aspect);
    }

    /**
     * @depends testServices
     */
    public function testExtension()
    {
        $client    = static::createClient();
        $container = $client->getContainer();

        $this->assertTrue($container->hasParameter('trivago.cacheable.disabled_methods'));
        $this->assertTrue($container->hasParameter('trivago.cacheable.default_ttl'));
        $this->assertTrue($container->hasParameter('trivago.cacheable.default_cache_service_adapter'));
    }
}