<?php

namespace Cache\CacheBundle\Tests\Unit\Service;

use Cache\CacheBundle\Service\CachingService;
use Cache\CacheBundle\Service\CachingServiceMethod;
use Cache\CacheBundle\Service\CachingServiceMethodCollection;
use Cache\CacheBundle\Tests\Unit\Stub\Cache\CacheItemPool;
use Cache\CacheBundle\Tests\Unit\Stub\Services\Foo;
use Cache\CacheBundle\Tests\Unit\TestCase;

/**
 * Class CachingServiceTest
 */
class CachingServiceTest extends TestCase
{
    public function testBad()
    {
        $cache = new CacheItemPool();
        $serviceA = new Foo();
        $serviceB = new Foo();

        $collection = new CachingServiceMethodCollection();
        $collection->add(new CachingServiceMethod($cache, $serviceB, 'publicMethod'));

        $sut = new CachingService($serviceA, $collection);
    }
}
