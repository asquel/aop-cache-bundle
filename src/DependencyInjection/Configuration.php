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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *strategy_name
 * @package   AsQuel\AopCacheBundle\DependencyInjection
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('trivago_aop_cache');
        $rootNode
            ->children()
                ->integerNode('default_ttl')->defaultValue(3600)->end()
                ->scalarNode('default_cache_service_adapter')->defaultValue(
                    'AsQuel\AopCacheBundle\Adapter\DoctrineCacheAdapter'
                )->end()
                ->arrayNode('disabled_methods')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
