<?php

namespace Cache\CacheBundle\Tests\Unit\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use Cache\CacheBundle\DependencyInjection\Configuration;

/**
 * Class ConfigurationTest
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @return Configuration
     */
    protected function getConfiguration()
    {
        return new Configuration();
    }

    /**
     *
     */
    public function testDependencyInjectionContainerValidConfiguration()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'dic' =>
                        [
                            'enabled' => true,
                            'services' =>
                                [
                                    'foo' =>
                                        [
                                            'methods' =>
                                                [
                                                    'bar' =>
                                                    [
                                                        'service_id' => 'some_service',
                                                        'ttl' => 10,
                                                    ]
                                                ],
                                        ],
                                ]
                        ],
                ],
            ],
            [
                'dic' =>
                    [
                        'enabled' => true,
                        'services' =>
                            [
                                'foo' =>
                                    [
                                        'methods' =>
                                            [
                                                'bar' =>
                                                    [
                                                        'service_id' => 'some_service',
                                                        'ttl' => 10,
                                                    ]
                                            ],
                                    ],
                            ]
                    ],
            ],
            'dic'
        );
    }
}
