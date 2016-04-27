# AOP Cache Bundle

This bundle allows "method caching" it takes the arguments of the method as a cache key (or the result from a specific method call)
and cache the result from the method.

It works with AOP (aspect oriented programming), if you want to know more about this subject, google is your friend.

Installation of goaop-symfony-bundle (https://github.com/goaop/goaop-symfony-bundle) is required.

## Config : 

```
asquel_aop_cache:
    default_ttl: 3600
    default_cache_service_adapter: AsQuel\CacheBundle\Adapters\DoctrineCacheAdapter
    disabled_methods:
      - ...
      - ...
```

* The default values (ttl, cache_service_adapter are overridable per annotation)
* Disabled_methods is an array of complete namespaced method names. If you write a name here it will desactivate the cache on this specific method

## Example 1:

```
    /**
     * @param $i
     * @Cacheable(ttl=3, cacheService="stash.default_cache", excludedArguments={}, strategy=@Serialization())
     */
    public function getValue($i) {
        return $this->values[$i];
    }
```
In this case, the result of the method getValue will be cached for 3 sec by the cache service "stash.default_cache", this service will
be used in the adapter specified in the Config (DoctrineCacheAdapter).
The strategy is serialization from parameters. That means, the cache key will be "md5(getValue.VALUE_OF_$i)"

## Example 2:

```
    /**
     * @param $i
     * @Cacheable(ttl=30, cacheService="stash.default_cache", cacheAdapter="AsQuel\Adapter\TestAdapter",excludedArguments={1}, strategy=@Serialization())
     */
    public function getValue($i, $y) {
        return $this->values[$i];
    }
```
In this case, the result of the method getValue will be cached for 30 sec by the cache service "stash.default_cache", this service will
be used in the adapter specified in the annotation AsQuel\Adapter\TestAdapter.
When the cache key will be generated, the argument $y (the second one) will be ignored (excludedArguments param in the annotation)
The strategy is serialization from parameters. That means, the cache key will be "md5(getValue.VALUE_OF_$i)" (because $y will be ignored)

## Example 3:

```
    /**
         * @return string
         * @Cacheable(
         *      ttl=10,
         *      cacheService="stash.default_cache",
         *      strategy=@MethodCall(argumentsMethodName={"AsQuel\AopCacheBundle\Tests\Service\ClassTest"="getId", "AsQuel\AopCacheBundle\Tests\Service\ClassTest2"="getId"}),
         *      excludedArguments={1}
         * )
         */
        public function test(ClassTest2 $o, $v, ClassTest $test, $var)
        {
            return 'haha';
        }
```
In this case, the result of the method getValue will be cached for 30 sec by the cache service "stash.default_cache".
Cache key will be a concatenation of : 
- method name 
- the result $o->getId()
(- $v is ignored)
- $test->getId()
- (string)$var