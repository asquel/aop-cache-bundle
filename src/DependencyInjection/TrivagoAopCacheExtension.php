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
namespace AsQuel\AopCacheBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class TrivagoAopCacheExtension
 *
 * @package   AsQuel\AopCacheBundle\DependencyInjection
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class TrivagoAopCacheExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $config    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configurator = new Configuration();
        $config       = $this->processConfiguration($configurator, $config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('trivago.cacheable.default_ttl', $config[ 'default_ttl' ]);
        $container->setParameter(
            'trivago.cacheable.default_cache_service_adapter',
            $config[ 'default_cache_service_adapter' ]
        );

        $disabledMethods = [];
        if (isset($config[ 'disabled_methods' ])) {
            $disabledMethods = $config[ 'disabled_methods' ];
        }
        $container->setParameter('trivago.cacheable.disabled_methods', $disabledMethods);
    }
}