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

use Cache\CacheBundle\DependencyInjection\CacheExtension;
use Cache\CacheBundle\Tests\Unit\ContainerTrait;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

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

    public function testDependencyInjectionContainerBuilder()
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
