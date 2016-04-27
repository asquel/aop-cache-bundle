<?php
namespace AsQuel\AopCacheBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AsQuel\AopCacheBundle\Tests\Service\TestServiceWithAnnotation;

/**
 * Class IntegrationTest
 *
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