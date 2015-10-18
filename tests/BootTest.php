<?php

namespace BEAR\Middleware;

use BEAR\Middleware\Exception\InvalidContextException;
use Ray\Di\InjectorInterface;

class BootTest extends AbstractBootTestCase
{
    /**
     * @var Boot
     */
    private $boot;

    public function setUp()
    {
        parent::setUp();
        $this->boot = new Boot;
    }

    public function testInvalidContext()
    {
        $this->setExpectedException(InvalidContextException::class);
        $injector = $this->boot->getInjector($this->appMeta, 'not_valid');
        $this->assertInstanceOf(InjectorInterface::class, $injector);
    }

    public function testGetInjector()
    {
        $injector = $this->boot->getInjector($this->appMeta, 'app');
        $this->assertInstanceOf(InjectorInterface::class, $injector);
    }
}
