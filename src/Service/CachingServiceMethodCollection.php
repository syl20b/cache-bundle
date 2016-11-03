<?php

namespace Cache\CacheBundle\Service;

/**
 * Class CachingServiceMethodCollection
 */
class CachingServiceMethodCollection
{
    private $items = [];

    public function add(CachingServiceMethod $method)
    {
        $this->items[$method->getName()] = $method;
    }

    public function get($name)
    {
        return $this->items[$name];
    }

    public function has($name)
    {
        return array_key_exists($name, $this->items);
    }
}
