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
     * @var CachingServiceMethodCollection
     */
    protected $methodCollection;

    /**
     * @param object $service
     */
    public function __construct($service, CachingServiceMethodCollection $methodCollection)
    {
        $this->service = $service;
        $this->methodCollection = $methodCollection;
    }

    /**
     *
     * @param string $name
     * @param array  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if ($this->methodCollection->has($name)) {
            $method = $this->methodCollection->get($name);
            return $method($arguments);
        } else {
            return call_user_func_array([$this->service, $name], $arguments);
        }
    }
}
