<?php
/**
 * aop-cache-bundle
 *
 * Copyright (c) 2015, AsQuel, Leipzig
 * All rights reserved.
 *
 * @since 2016-04-08
 * @author Software Engineering Leipzig <team.leipzig@trivago.com>
 * @author Roman Lasinski <roman.lasinski@trivago.com>
 * @copyright 2015 (c) AsQuel, Leipzig
 * @license All rights reserved.
 */
namespace AsQuel\AopCacheBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AsQuel\AopCacheBundle\Tests\Service\TestServiceWithAnnotation;

/**
 * Class IntegrationTest
 *
 * @author Software Engineering Leipzig <team.leipzig@trivago.com>
 * @author Roman Lasinski <roman.lasinski@trivago.com>
 * @copyright 2015 (c) AsQuel, Leipzig
 * @license All rights reserved.
 */
class IntegrationTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        parent::setUp();

        static::bootKernel();
        $this->container = static::$kernel->getContainer();
    }

    public function testCachedService()
    {
        /** @var TestServiceWithAnnotation $service */
        $service = $this->container->get('test.service.with.annotation');

        $this->assertSame('test1', $service->getValue('1'));
        $this->assertSame('test2', $service->getValue('2'));
    }
}