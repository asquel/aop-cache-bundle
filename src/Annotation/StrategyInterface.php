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
namespace AsQuel\AopCacheBundle\Annotation;

/**
 * Class StrategyInterface
 *
 * @package   AsQuel\AopCacheBundle\Annotation
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
interface StrategyInterface
{
    /**
     * @param array $arguments
     *
     * @return string
     */
    public function getCacheKeyByArguments(array $arguments);
}