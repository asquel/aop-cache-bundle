<?php
/**
 * Cache bundle
 *
 * Copyright (c) 2012-2013, AsQuel
 * All rights reserved.
 *
 * @since 13.06.13
 *
 * @author Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Class TestKernel
 *
 *
 * @author Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class TestKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Go\Symfony\GoAopBundle\GoAopBundle(),
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Tedivm\StashBundle\TedivmStashBundle(),
            new AsQuel\AopCacheBundle\AsQuelAopCacheBundle()
        );

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config.yml');
    }
}