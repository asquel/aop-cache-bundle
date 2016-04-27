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
use AsQuel\AopCacheBundle\Exception\ArgumentListException;
use AsQuel\AopCacheBundle\Exception\MethodNotExistsException;

/**
 * Class MethodCall
 *
 * @package   AsQuel\AopCacheBundle\Annotation
 *
 * @author    Axel Barbier <axel.barbier@gmail.com>
 * @copyright 2012-2013 AsQuel
 *
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class MethodCall implements StrategyInterface
{
    /**
     * @var array<string>
     */
    public $argumentsMethodName;

    /**
     * @inheritdoc
     */
    public function getCacheKeyByArguments(array $arguments)
    {
        $this->checkArguments($arguments);

        $key = '';
        foreach ($arguments as $argument) {
            $key .= (string) $this->getValueForArg($argument);
        }

        return $key;
    }

    /**
     * @param $args
     *
     * @throws ArgumentListException
     */
    public function checkArguments($args)
    {
        foreach ($args as $arg) {
            if (!is_object($arg)) {
                continue;
            }

            if (!isset($this->argumentsMethodName[ get_class($arg) ])) {
                throw new ArgumentListException(
                    'Argument ' . get_class($arg) . ' is not defined in your argumentsMethodName list in the annotation'
                );
            }
        }
    }

    /**
     * @param $arg
     *
     * @return mixed
     * @throws MethodNotExistsException
     */
    public function getMethodNameForArgument($arg)
    {
        foreach ($this->argumentsMethodName as $objectNamespace => $methodName) {
            if (get_class($arg) === $objectNamespace) {
                return $methodName;
            }
        }
        throw new MethodNotExistsException(
            'The provided argument namespace is not defined in the annotation ' . get_class($arg)
        );
    }

    /**
     * @param $arg
     *
     * @return string
     * @throws MethodNotExistsException
     */
    public function getValueForArg($arg)
    {
        if (is_object($arg)) {
            $methodName = $this->getMethodNameForArgument($arg);

            if (!method_exists($arg, $methodName)) {
                throw new MethodNotExistsException(
                    'Method ' . $methodName . ' do not exists in object ' . get_class($arg)
                );
            }
            return (string)call_user_func(array($arg, $methodName));
        } else {
            return (string)$arg;
        }
    }
}