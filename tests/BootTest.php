<?php

namespace BEAR\Middleware;

use BEAR\Middleware\Exception\InvalidContextException;
use Ray\Di\InjectorInterface;

class BootTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Boot
     */
    private $boot;

    /**
     * @var FakeAppMeta
     */
    private $appMeta;

    public function setUp()
    {
        parent::setUp();
        $this->boot = new Boot;
        $this->appMeta = new FakeAppMeta;
        $this->appMeta->name = 'BEAR\Middleware';
        $this->appMeta->tmpDir = __DIR__ . '/tmp';
        $unlink = function ($path) use (&$unlink) {
            foreach (glob(rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*') as $file) {
                is_dir($file) ? $unlink($file) : unlink($file);
                @rmdir($file);
            }
        };
        $unlink($this->appMeta->tmpDir);
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
