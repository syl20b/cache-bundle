<?php

namespace Cache\CacheBundle\Tests\Unit\Service;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Cache\CacheBundle\Tests\Unit\Stub\Services\Bar;
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
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /Name must be a string, "\w+" given/
     */
    public function testInvalidNameArgumentInConstructor()
    {
        $cache = new CacheItemPool();
        $service = new Foo();
        $name = [];
        $config = [];
        $sut = new CachingServiceMethod($cache, $service, $name, $config);
    }

    /**
     * @expectedException \DomainException
     * @expectedExceptionMessageRegExp /Method "\w+" not found in class "[^"]*"/
     */
    public function testServiceMethodNotFound()
    {
        $cache = new CacheItemPool();
        $service = new Foo();
        $name = uniqid('name');
        $config = [];
        $sut = new CachingServiceMethod($cache, $service, $name, $config);
    }

    /**
     *
     */
    public function testWithCallMagicMethod()
    {
        $cachePool = new ArrayCachePool();
        $service = new Bar();
        $name = 'inaccessibleMethod';
        $config = [];
        $expected = '__call';
        $sut = new CachingServiceMethod($cachePool, $service, $name, $config);
        $this->assertEquals($expected, $sut());
    }

    /**
     *
     */
    public function testInvokeWhenKeyNotFound()
    {
        $cachePool = new ArrayCachePool();
        $service = new Foo();
        $name = 'methodWithArgument';
        $config = [];
        $arg = uniqid('arg');
        $expected = call_user_func_array([$service, $name], [$arg]);

        $sut = new CachingServiceMethod($cachePool, $service, $name, $config);
        $this->assertEquals($expected, $sut($arg));
    }

    /**
     *
     */
    public function testGetName()
    {
        $name = 'methodWithArgument';
        $sut = new CachingServiceMethod(
            new ArrayCachePool(),
            new Foo(),
            $name,
            []
        );
        $this->assertEquals($name, $sut->getName());
        $this->assertEquals('Cache\CacheBundle\Tests\Unit\Stub\Services\Foo::'.$name, $sut->getFullName());
    }
}
