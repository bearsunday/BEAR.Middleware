<?php

namespace BEAR\Middleware;

use BEAR\Middleware\Annotation\Stream;
use Ray\Di\Injector;

class ResolverTest extends \PHPUnit_Framework_TestCase
{
    public function test__invoke()
    {
        $resolver = new Resolver(new Injector);
        $instance = $resolver(Stream::class);
        $this->assertInstanceOf(Stream::class, $instance);
        $instance = $resolver([Stream::class]);
        $this->assertInstanceOf(Stream::class, $instance);
        $instance = $resolver(true);
        $this->assertTrue($instance);
    }
}
