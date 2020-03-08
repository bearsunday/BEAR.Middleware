<?php
/**
 * This file is part of the BEAR.Middleware package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware;

use BEAR\Package\Exception\InvalidContextException;
use Ray\Di\InjectorInterface;

class BootTest extends AbstractBootTestCase
{
    /**
     * @var Boot
     */
    private $boot;

    public function setUp() : void
    {
        parent::setUp();
        $this->boot = new Boot;
    }

    public function testInvalidContext()
    {
        $this->expectException(InvalidContextException::class);
        $injector = $this->boot->getInjector($this->appMeta, 'not_valid');
        $this->assertInstanceOf(InjectorInterface::class, $injector);
    }

    public function testGetInjector()
    {
        $injector = $this->boot->getInjector($this->appMeta, 'app');
        $this->assertInstanceOf(InjectorInterface::class, $injector);
    }
}
