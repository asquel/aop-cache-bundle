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
namespace AsQuel\AopCacheBundle\Service;

use Go\Aop\Aspect;
use Go\Aop\Intercept\MethodInvocation;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use AsQuel\AopCacheBundle\Adapter\CacheInterface;
use AsQuel\AopCacheBundle\Annotation\Cacheable;
use AsQuel\AopCacheBundle\Annotation\StrategyInterface;
use AsQuel\AopCacheBundle\Exception\ArgumentListException;
use AsQuel\AopCacheBundle\Exception\MethodNotExistsException;
use AsQuel\AopCacheBundle\Exception\NotCacheableException;
use AsQuel\AopCacheBundle\Exception\StrategyException;

use Go\Lang\Annotation as Go;

/**
 * Class CachingAspect
 *
 * @package   AsQuel\HSI\Bundle\HotelBundle\Service
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class CachingAspect implements Aspect
{
    use ContainerAwareTrait;

    /**
     * @var CacheInterface[]
     */
    private $caches = [];

    /**
     * @var int
     */
    private $defaultTtl = 0;

    /**
     * @var string
     */
    private $defaultCacheAdapterClass = '';

    /**
     * @var array
     */
    private $disabledMethods = [];

    /**
     * @param string $defaultCacheAdapterClass
     * @param array  $disabledMethods
     * @param int    $defaultTtl
     */
    public function __construct($defaultCacheAdapterClass, array $disabledMethods, $defaultTtl = 3600)
    {
        $this->defaultCacheAdapterClass = $defaultCacheAdapterClass;
        $this->disabledMethods          = $disabledMethods;
        $this->defaultTtl               = $defaultTtl;
    }

    /**
     * @param MethodInvocation $invocation
     *
     * @return mixed|null
     *
     * @Go\Around("@execution(AsQuel\AopCacheBundle\Annotation\Cacheable)")
     */
    public function aroundCacheable(MethodInvocation $invocation)
    {
        // This specific cache annotation has been disabled by config
        $methodName = get_class($invocation->getThis()) . '::' . $invocation->getMethod()->getName();
        if (in_array($methodName, $this->disabledMethods, false)) {
            return $invocation->proceed();
        }

        /**
         * @var Cacheable $annotation
         */
        $annotation   = $invocation->getMethod()->getAnnotation('AsQuel\AopCacheBundle\Annotation\Cacheable');
        $args         = $invocation->getArguments();
        $argsFiltered = $this->filterArguments($annotation, $args);
        $key          = $this->generateCacheKey($argsFiltered, $methodName, $annotation);

        $itemCached = $this->getCacheAdapter($annotation)->get($key);

        if ($itemCached === false) {
            $methodResult = $invocation->proceed();

            $ttl = $this->defaultTtl;
            if ($annotation->ttl) {
                $ttl = $annotation->ttl;
            }

            $this->getCacheAdapter($annotation)->set($key, $methodResult, $ttl);
        } else {
            $methodResult = $itemCached;
        }

        return $methodResult;
    }

    /**
     * @param Cacheable $annotation
     *
     * @return CacheInterface
     * @throws NotCacheableException
     */
    public function getCacheAdapter(Cacheable $annotation)
    {
        $cacheAdapterClass = $this->defaultCacheAdapterClass;
        if ($annotation->cacheAdapter) {
            $cacheAdapterClass = $annotation->cacheAdapter;
        }

        $annotationId = md5($cacheAdapterClass . $annotation->cacheService);
        if (!isset($this->caches[$annotationId])) {
            try {
                $cacheService = $this->container->get($annotation->cacheService);
                $this->caches[$annotationId] = new $cacheAdapterClass($cacheService);

            } catch (\Exception $e) {
                throw new NotCacheableException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return $this->caches[$annotationId];
    }

    /**
     * @param Cacheable $annotation
     * @param array     $args
     *
     * @return array
     */
    public function filterArguments(Cacheable $annotation, array $args)
    {
        if (!$annotation->excludedArguments ||
            ($annotation->excludedArguments && count($annotation->excludedArguments) == 0)
        ) {
            return $args;
        }

        $newArgsList = [];
        foreach ($args as $numArg => $arg) {
            if (in_array($numArg, $annotation->excludedArguments, true)) {
                continue;
            }
            $newArgsList[] = $arg;
        }
        return $newArgsList;
    }

    /**
     * @param array     $args
     * @param string    $annotatedMethodName
     * @param Cacheable $annotation
     *
     * @return string
     * @throws MethodNotExistsException
     * @throws StrategyException
     * @throws ArgumentListException
     */
    public function generateCacheKey(array $args, $annotatedMethodName, Cacheable $annotation)
    {
        $strategy = $annotation->strategy;

        if (!$strategy instanceof StrategyInterface) {
            throw new StrategyException('Invalid strategy provided.');
        }
        return md5($annotatedMethodName . $strategy->getCacheKeyByArguments($args));
    }
}