<?php

namespace Cache\CacheBundle\Service;

use Cache\Taggable\TaggableItemInterface;
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

        if (!(method_exists($service, $name) || method_exists($service, '__call'))) {
            throw new \DomainException(
                sprintf('Method "%s" not found in class "%s"', $name, get_class($service))
            );
        }

        $this->cache = $cache;
        $this->service = $service;
        $this->name = $name;
        $this->config = $config;
        if (isset($config['use_tagging'])) {
            $this->tagHandler = isset($config['tag_handler']) ? $config['tag_handler'] : null;
        }
    }

    /**
     * Invoke method
     *
     * @return mixed
     */
    public function __invoke()
    {
        $arguments = func_get_args();

        $key = $this->generateCacheKey($arguments);
        $item = $this->cache->getItem($key);

        if (!$item->isHit()) {
            $item
                ->set(call_user_func_array([$this->service, $this->name], $arguments))
//                ->expiresAfter()
            ;


            if ($item instanceof TaggableItemInterface && isset($this->config['tag_handler'])) {

                $item->addTag();
            }

            $this->cache->save($item);
        }

        return $item->get();
    }

    /**
     * Get method name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get full method name
     *
     * @return string
     */
    public function getFullName()
    {
        return get_class($this->service).'::'.$this->getName();
    }

    /**
     * Generate cache key
     *
     * @param array $arguments
     * @return string
     */
    public function generateCacheKey(array $arguments)
    {
        return md5(sprintf('%s.%s', $this->getFullName(), serialize($arguments)));
    }
}
