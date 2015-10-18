<?php

namespace BEAR\Middleware;

use BEAR\Middleware\Handler\ResourceHandler;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Relay\RelayBuilder;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;

class BootstrapTest extends AbstractBootTestCase
{
    /**
     * header() $args spied here at tests/Fake/header.php
     *
     * @var string
     */
    public static $headerArgs;

    public function testBootstrap()
    {
        $this->expectOutputString('{"msg":"hello world","stream":"Konichiwa stream !
"}');
        $this->boot = new Boot;
        $appMeta = new FakeAppMeta;
        $appMeta->name = 'BEAR\Middleware';
        $appMeta->tmpDir = __DIR__ . '/tmp';

        $injector = (new Boot)->getInjector($appMeta, 'app');
        $relayBuilder = new RelayBuilder(new Resolver($injector));
        $queue = [
            ResponseSender::class,
            ExceptionHandler::class,
            ResourceHandler::class
        ];
        $relay = $relayBuilder->newInstance($queue);
        $request = ServerRequestFactory::fromGlobals()->withUri(new Uri('http://localhost/item'));
        $reponce = new Response();
        $relay($request, $reponce);
        $this->assertSame('Content-Type: application/json', self::$headerArgs);
    }
}
