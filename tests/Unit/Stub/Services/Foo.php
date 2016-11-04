<?php

namespace Cache\CacheBundle\Tests\Unit\Stub\Services;

class Foo
{
    public function publicMethod()
    {

    }

    public function methodWithArgument($arg)
    {
        return $arg;
    }

    static public function staticPublicMethod()
    {

    }

    protected function protectedMethod()
    {

    }

    private function privateMethod()
    {

    }
}
