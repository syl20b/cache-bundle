<?php

namespace Cache\CacheBundle\Service;

/**
 * Class CachingService
 */
class CachingService
{
    /**
     * @var object
     */
    protected $service;

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @param object $service
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * Add
     *
     * @param CachingServiceMethod $method
     * @return CachingService
     */
    public function addMethod(CachingServiceMethod $method)
    {
        $this->methods[$method->getName()] = $method;

        return $this;
    }

    /**
     * Caching service has method
     *
     * @param string $name
     * @return bool
     */
    public function hasMethod($name)
    {
        return array_key_exists($name, $this->methods);
    }

    /**
     *
     *
     * @param string $name
     * @param array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if ($this->hasMethod($name)) {
            return $this->methods[$name]($arguments);
        } else {
            return call_user_func_array([$this->service, $name], $arguments);
        }
    }
}
