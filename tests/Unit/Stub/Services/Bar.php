<?php

namespace Cache\CacheBundle\Tests\Unit\Stub\Services;

class Bar
{
    public function onBarCall()
    {

    }

    public function onBazCall()
    {

    }

    public function __call($name, $arguments)
    {
        if ($name == 'inaccessibleMethod') {
            return '__call';
        }
    }
}
