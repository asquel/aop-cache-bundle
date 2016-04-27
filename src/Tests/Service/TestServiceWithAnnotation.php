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

use AsQuel\AopCacheBundle\Annotation as Cache;

/**
 * Class TestServiceWithAnnotation
 *
 * @package   AsQuel\AopCacheBundle\Tests
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class TestServiceWithAnnotation
{
    /**
     * @var array
     */
    private $values;

    public function __construct()
    {
        $this->values = [
            '1' => 'test1',
            '2' => 'test2',
            '3' => 'test3'
        ];
    }

    /**
     * @Cache\Cacheable(
     *      ttl=30,
     *      cacheService="doctrine_cache.providers.arrayDoctrineCache",
     *      excludedArguments={},
     *      strategy=@Cache\Strategy\Serialization()
     * )

     * @param string $i
     *
     * @return string
     */
    public function getValue($i)
    {
        return $this->values[$i];
    }

    /**
     * @Cache\Cacheable(
     *      ttl=33,
     *      cacheService="doctrine_cache.providers.arrayDoctrineCache",
     *      excludedArguments={1,3},
     *      strategy=@Cache\Strategy\Serialization()
     * )
     *
     * @param $i
     * @param $j
     * @param $k
     * @param $l
     *
     * @return string
     */
    public function getValue2($i, $j, $k, $l)
    {
        return 'x';
    }

    /**
     * @Cache\Cacheable(
     *      ttl=10,
     *      cacheService="doctrine_cache.providers.arrayDoctrineCache",
     *      excludedArguments={},
     *      strategy=@Cache\Strategy\Serialization()
     * )
     *
     * @return string
     */
    public function forCacheAdapter()
    {
        return 'hoho';
    }

    /**
     * @Cache\Cacheable(
     *      ttl=10,
     *      cacheService="",
     *      excludedArguments={},
     *      strategy=@Cache\Strategy\Serialization()
     * )
     *
     * @return string
     */
    public function strategyName1()
    {
        return 'haha';
    }

    /**
     * @Cache\Cacheable(
     *      ttl=10,
     *      cacheService="",
     *      strategy=@Cache\Strategy\MethodCall(
     *          argumentsMethodName={"AsQuel\AopCacheBundle\Tests\Service\ClassTest"="getId"}
     *      )
     * )
     *
     * @param ClassTest $test
     * @param           $var
     *
     * @return string
     */
    public function strategyName2(ClassTest $test, $var)
    {
        return 'haha';
    }

    /**
     * @Cache\Cacheable(
     *      ttl=10,
     *      cacheService="",
     *      strategy=@Cache\Strategy\MethodCall(
     *          argumentsMethodName={
     *              "AsQuel\AopCacheBundle\Tests\Service\ClassTest"="getId",
     *              "AsQuel\AopCacheBundle\Tests\Service\ClassTest2"="getId"
     *          }
     *      ),
     *      excludedArguments={1}
     * )
     *
     * @param ClassTest2 $o
     * @param            $v
     * @param ClassTest  $test
     * @param            $var
     *
     * @return string
     */
    public function strategyName3(ClassTest2 $o, $v, ClassTest $test, $var)
    {
        return 'haha';
    }

    /**
     * @Cache\Cacheable(
     *      ttl=10,
     *      cacheService="",
     *      strategy=@Cache\Strategy\MethodCall(
     *          argumentsMethodName={"Wrong\Namespace\ClassTest"="getId"}
     *      )
     * )
     *
     * @param ClassTest $test
     * @param           $var
     * @param \stdClass $o
     *
     * @return string
     */
    public function wrongAnnotation(ClassTest $test, $var, \stdClass $o)
    {
        return 'hoho';
    }
}