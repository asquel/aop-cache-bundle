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

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Cacheable
 *
 * @package   AsQuel\HSI\Bundle\HotelBundle\Service
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 *
 * @Annotation
 * @Target({"METHOD"})
 */
class Cacheable
{
    /**
     * @var integer
     */
    public $ttl;

    /**
     * @var string
     * @Annotation\Required()
     */
    public $cacheService;

    /**
     * If you want to override the default cache adapter selected by config
     *
     * @var string
     */
    public $cacheAdapter;

    /**
     * @var array<integer>
     */
    public $excludedArguments;

    /**
     * @var \AsQuel\AopCacheBundle\Annotation\StrategyInterface
     * @Annotation\Required()
     */
    public $strategy;
}