<?php

namespace Cache\CacheBundle\Service;

use Cache\Adapter\Common\CacheItem;
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
     * @var array
     */
    private $config = [];

    /**
     * CachingServiceMethod constructor.
     *
     * @param CacheItemPoolInterface $cache
     * @param object                 $service
     * @param string                 $name
     * @param array                  $config
     */
    public function __construct(CacheItemPoolInterface $cache, $service, $name, array $config = [])
    {
        if (!is_object($service)) {
            throw new \InvalidArgumentException(
                sprintf('Service must be an object, "%s" given',  gettype($service))
            );
        }

        if (!is_string($name)) {
            throw new \InvalidArgumentException(
                sprintf('Name must be a string, "%s" given',  gettype($name))
            );
        }

        if (!method_exists($service, $name)) {
            throw new \DomainException(
                sprintf('Method "%s" not found in class "%s"', $name, get_class($service))
            );
        }

        $this->cache = $cache;
        $this->service = $service;
        $this->name = $name;
        $this->config = $config;
    }

    /**
     * Invoke method
     *
     * @return mixed
     */
    public function __invoke()
    {
        $arguments = func_get_args();

        $key = md5(serialize($arguments));

        if (!$this->cache->hasItem($key)) {
            $item = new CacheItem($key);
            $item->set(call_user_func_array([$this->service, $this->name], $arguments));
            $this->cache->save($item);
        }

        return $this->cache->getItem($key)->get();
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return get_class($this->service).'::'.$this->getName();
    }
}
