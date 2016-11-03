<?php

namespace Cache\CacheBundle\Tests\Unit\Service;

use Cache\CacheBundle\Tests\Unit\Stub\Services\Foo;
use Cache\CacheBundle\Tests\Unit\TestCase;
use Cache\CacheBundle\Service\CachingServiceMethod;
use Cache\CacheBundle\Tests\Unit\Stub\Cache\CacheItemPool;


/**
 * Class CachingServiceMethodTest
 */
class CachingServiceMethodTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /Service must be an object, "\w+" given/
     */
    public function testInvalidServiceArgumentInConstructor()
    {
        $cache = new CacheItemPool();
        $service = uniqid();
        $name = '';
        $config = [];
        $sut = new CachingServiceMethod($cache, $service, $name, $config);
    }

    /**
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /Method "\w+" not found in class "[^"]*"/
     */
    public function testInvalidNameArgumentInConstructor()
    {
        $cache = new CacheItemPool();
        $service = new Foo();
        $name = uniqid('name');
        $config = [];
        $sut = new CachingServiceMethod($cache, $service, $name, $config);
    }
}
