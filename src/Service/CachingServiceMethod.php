<?php


namespace Cache\CacheBundle\Service;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Class CachingServiceMethod
 */
class CachingServiceMethod
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var object
     */
    private $service;

    /**
     * @var string
     */
    private $name;

    /**
     * CachingServiceMethod constructor.
     *
     * @param CacheItemPoolInterface $cache
     * @param object                 $service
     * @param string                 $name
     * @param array                  $config
     */
    public function __construct(CacheItemPoolInterface $cache, $service, $name, array $config)
    {
        $this->cache = $cache;
        $this->service = $service;
        $this->name = $name;
    }

    public function __invoke($arguments)
    {
        $key = serialize($arguments);

        if (!$this->cache->hasItem($key)) {
            return call_user_func_array([$this->service, $this->name], $arguments);
        }

        return $this->cache->getItem($key);
    }
}
