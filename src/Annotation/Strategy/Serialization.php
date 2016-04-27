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
namespace AsQuel\AopCacheBundle\Annotation\Strategy;

use Doctrine\Common\Annotations\Annotation;
use AsQuel\AopCacheBundle\Annotation\Strategy;
use AsQuel\AopCacheBundle\Annotation\StrategyInterface;

/**
 * Class SerializeStrategy
 *
 * @package   AsQuel\AopCacheBundle\Annotation
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Serialization implements StrategyInterface
{
    /**
     * @inheritdoc
     */
    public function getCacheKeyByArguments(array $arguments)
    {
        return serialize($arguments);
    }
}