<?php
/**
 * aop-cache-bundle
 *
 * Copyright (c) 2012-2013, AsQuel
 * All rights reserved.
 *
 * @since     2/4/16
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
namespace AsQuel\AopCacheBundle\Tests\Service;

use Go\Symfony\GoAopBundle\Kernel\AspectSymfonyKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AsQuel\AopCacheBundle\Adapter\CacheInterface;
use AsQuel\AopCacheBundle\Service\CachingAspect;
use AsQuel\AopCacheBundle\Tests\Helper\AnnotationHelper;


/**
 * Class CacheableTest
 *
 * @package   AsQuel\AopCacheBundle\Tests\Service
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class CacheableTest extends WebTestCase
{

    const CLASS_WITH_ANNOTATION = 'AsQuel\AopCacheBundle\Tests\Service\TestServiceWithAnnotation';

    /**
     * @return CachingAspect
     */
    private function getCachingAspect()
    {
        $client    = static::createClient();
        $container = $client->getContainer();

        return $container->get('trivago.aspect.cacheable');
    }

    public function testGetAspect() {
        $client    = static::createClient();
        $container = $client->getContainer();

        $cacheAsp = $container->get('trivago.aspect.cacheable');
        /**
         * @var CacheInterface $cacheServ
         */
        $cacheServ = $container->get('doctrine_cache.providers.arrayDoctrineCache');

        $serviceWithAnnot = $container->get('test.service.with.annotation');
        $this->assertEquals('test1',$serviceWithAnnot->getValue(1));

        $this->assertTrue($cacheServ->contains(md5('AsQuel\AopCacheBundle\Tests\Service\TestServiceWithAnnotation::getValue'.serialize(1))));

        $serviceWithAnnot->getValue(1);
    }

    public function testGenerateCacheKey()
    {
        $cacheableAspect = $this->getCachingAspect();
        $annotation      = AnnotationHelper::getCacheableAnnotationOnMethod('getValue', static::CLASS_WITH_ANNOTATION);

        $cacheKey = $cacheableAspect->generateCacheKey(array('1'), 'getValue', $annotation);

        $this->assertEquals(md5('getValue' . serialize(array('1'))), $cacheKey);
    }

    public function testGenerateCacheKeyWithFilteredArgument()
    {
        $cacheableAspect = $this->getCachingAspect();
        $annotation      = AnnotationHelper::getCacheableAnnotationOnMethod('getValue2', static::CLASS_WITH_ANNOTATION);
        $args            = [1, 2, 3, 4];
        $filteredArgs    = $cacheableAspect->filterArguments($annotation, $args);

        $this->assertCount(2, $filteredArgs);
        //2 and 4 were filtered
        $this->assertEquals(1, $filteredArgs[ 0 ]);
        $this->assertEquals(3, $filteredArgs[ 1 ]);
        $cacheKey = $cacheableAspect->generateCacheKey($filteredArgs, 'getValue2', $annotation);

        $this->assertEquals(md5('getValue2' . serialize(array(1, 3))), $cacheKey);
    }

    public function testStrategy()
    {
        $annotation = AnnotationHelper::getCacheableAnnotationOnMethod('strategyName1', static::CLASS_WITH_ANNOTATION);

        $this->assertInstanceOf('AsQuel\AopCacheBundle\Annotation\Strategy\Serialization', $annotation->strategy);
        $annotation2 = AnnotationHelper::getCacheableAnnotationOnMethod('strategyName2', static::CLASS_WITH_ANNOTATION);
        $this->assertInstanceOf('AsQuel\AopCacheBundle\Annotation\Strategy\MethodCall', $annotation2->strategy);
        $argumentsMethodName = $annotation2->strategy->argumentsMethodName;

        $this->assertCount(1, $argumentsMethodName);
        $this->assertArrayHasKey("AsQuel\AopCacheBundle\Tests\Service\ClassTest", $argumentsMethodName);
        $this->assertEquals("getId", $argumentsMethodName[ "AsQuel\AopCacheBundle\Tests\Service\ClassTest" ]);
    }

    public function testGenerateCacheKeyWithMethodCall()
    {
        $cacheableAspect = $this->getCachingAspect();
        $annotation      = AnnotationHelper::getCacheableAnnotationOnMethod(
            'strategyName2',
            static::CLASS_WITH_ANNOTATION
        );
        $args            = [
            new ClassTest(),
            'xx'
        ];
        $generatedKey    = $cacheableAspect->generateCacheKey($args, 'strategyName2', $annotation);
        $key             = md5('strategyName2' . '1' . 'xx');

        $this->assertEquals($key, $generatedKey);
    }

    public function testGenerateCacheKeyWithMethodCallAndArgumentsExclusion()
    {
        $cacheableAspect = $this->getCachingAspect();
        $annotation      = AnnotationHelper::getCacheableAnnotationOnMethod(
            'strategyName3',
            static::CLASS_WITH_ANNOTATION
        );
        $args            = [
            new ClassTest2(),
            'pp',
            new ClassTest(),
            'xx'
        ];
        $filteredArgs    = $cacheableAspect->filterArguments($annotation, $args);
        $generatedKey    = $cacheableAspect->generateCacheKey($filteredArgs, 'strategyName3', $annotation);
        $key             = md5('strategyName3' . '1' . '1' . 'xx');

        $this->assertEquals($key, $generatedKey);
    }


    public function testStrategyMethodNameForArg()
    {
        $annotation2 = AnnotationHelper::getCacheableAnnotationOnMethod('strategyName2', static::CLASS_WITH_ANNOTATION);
        $args        = [
            new ClassTest(),
            'xx'
        ];

        $this->assertEquals("1", $annotation2->strategy->getValueForArg($args[ 0 ]));
        $this->assertEquals('xx', $annotation2->strategy->getValueForArg($args[ 1 ]));
    }

    /**
     * @expectedException   \AsQuel\AopCacheBundle\Exception\ArgumentListException
     */
    public function testStrategyException()
    {
        $annotation = AnnotationHelper::getCacheableAnnotationOnMethod(
            'wrongAnnotation',
            static::CLASS_WITH_ANNOTATION
        );
        $args       = [
            new ClassTest(),
            'xx'
        ];
        $annotation->strategy->checkArguments($args);
    }

    public function testGetCacheAdapter()
    {
        $cacheableAspect     = $this->getCachingAspect();
        $cacheableAnnotation = AnnotationHelper::getCacheableAnnotationOnMethod(
            'forCacheAdapter',
            static::CLASS_WITH_ANNOTATION
        );

        $adapter = $cacheableAspect->getCacheAdapter($cacheableAnnotation);

        $this->assertInstanceOf('AsQuel\AopCacheBundle\Adapter\DoctrineCacheAdapter', $adapter);
    }
}