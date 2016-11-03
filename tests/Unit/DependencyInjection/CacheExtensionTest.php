<?php

/*
 * This file is part of php-cache\cache-bundle package.
 *
 * (c) 2015 Aaron Scherer <aequasi@gmail.com>, Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cache\CacheBundle\Tests\Unit\DependencyInjection;

use Cache\CacheBundle\Tests\Unit\Stub\Cache\CacheItemPool;
use Cache\CacheBundle\Tests\Unit\Stub\Services\Foo;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Cache\CacheBundle\DependencyInjection\CacheExtension;
use Cache\CacheBundle\Tests\Unit\ContainerTrait;

/**
 * Class CacheExtensionTest.
 *
 * @author Aaron Scherer <aequasi@gmail.com>
 */
class CacheExtensionTest extends AbstractExtensionTestCase
{
    use ContainerTrait;

    public function testRouterBuilder()
    {
        $container = $this->createContainerFromFile('router');

        $config = $container->getParameter('cache.router');

        $this->assertTrue(isset($config['enabled']));

        $this->assertTrue($config['enabled']);
        $this->assertEquals($config['service_id'], 'default');
    }

/*    public function testDependencyInjectionContainerBuilder()
    {
        $container = $this->createContainerFromFile('dic');

        $this->assertTrue($container->has('decorated_service_foo'));
        $this->assertTrue($container->has('decorated_service_bar'));

        $config = $container->getParameter('cache.dic');

        $this->assertTrue(isset($config['enabled']));
        $this->assertTrue($config['enabled']);

        $this->assertTrue($container->has('decorated_service_foo'));
        $this->assertTrue($container->has('decorated_service_bar'));

        $this->assertTrue($container->has('cache.service.dic.decorated_service_foo'));
        $this->assertTrue($container->has('cache.service.dic.decorated_service_bar'));

        $this->assertTrue($container->get('cache.service.dic.decorated_service_foo')->hasMethod('publicMethod'));
    }
*/
    /**
     *
     */
    public function testAfterLoadingTheCorrectConfiguration()
    {
        $this->setParameter('kernel.debug', false);
        $this->registerService('cache_item_pool', new CacheItemPool());
        $this->registerService('decorated_service_foo', new Foo());

        $this->load(
            [
                'dic' =>
                    [
                        'enabled' => true,
                        'services' =>
                            [
                                'decorated_service_foo' =>
                                    [
                                        'methods' =>
                                            [
                                                'publicMethod' =>
                                                    [
                                                        'service_id' => 'cache_item_pool',
                                                        'ttl' => 10,
                                                    ]
                                            ],
                                    ],
                            ]
                    ],
            ]
        );

        $this->assertContainerBuilderHasService('cache_item_pool');
        $this->assertContainerBuilderHasService('decorated_service_foo');
        $this->assertContainerBuilderHasService('cache.service.dic.decorated_service_foo');
    }

    /**
     * @return array
     */
    protected function getContainerExtensions()
    {
        return [
            new CacheExtension(),
        ];
    }
}
