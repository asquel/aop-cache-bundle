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
namespace AsQuel\AopCacheBundle\Tests\Helper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use AsQuel\AopCacheBundle\Annotation\Cacheable;

/**
 * Class AnnotationHelper
 *
 * @package   Asquel
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class AnnotationHelper
{
    /**
     * Register an autoloader
     */
    public static function registerAnnotations()
    {
        $loader = include __DIR__ . '/../../../vendor/autoload.php';
        AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
    }

    /**
     * @param $methodName
     * @param $class
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getCacheableAnnotationOnMethod($methodName, $class)
    {
        $reader    = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($class);

        $methodAnnotations = $reader->getMethodAnnotations($reflectionClass->getMethod($methodName));

        foreach ($methodAnnotations as $annotation) {
            if ($annotation instanceof Cacheable) {
                return $annotation;
            }
        }
        throw new \Exception('Annotation not found');
    }
}