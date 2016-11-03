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
    public function __construct(CacheItemPoolInterface $cache, $service, string $name, array $config = [])
    {
        if (!is_object($service)) {
            throw new \InvalidArgumentException(
                sprintf('Service must be an object, "%s" given',  gettype($service))
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
     * @param array $arguments
     * @return Psr\Cache\CacheItemInterface
     */
    public function __invoke(array $arguments)
    {
        $key = serialize($arguments);

        if (!$this->cache->hasItem($key)) {
            return call_user_func_array([$this->service, $this->name], $arguments);
        }

        return $this->cache->getItem($key);
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
}
