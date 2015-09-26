<?php

use BEAR\Middleware\Handler\ResourceHandler;
use BEAR\Middleware\Module\MiddlewareModule;
use BEAR\Middleware\Resolver;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Ray\Di\Injector;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Relay\RelayBuilder;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

loader: {
    $loader = require dirname(dirname(__DIR__)) . '/vendor/autoload.php';
    AnnotationRegistry::registerLoader([$loader, 'loadClass']);
}
init: {
    $injector = new Injector(new MiddlewareModule('BEAR\Middleware'));
    $relayBuilder = new RelayBuilder(new Resolver($injector));
    $queue = [
        ResponseSender::class,
        ExceptionHandler::class,
        ResourceHandler::class
    ];
    $relay = $relayBuilder->newInstance($queue);
    $request = ServerRequestFactory::fromGlobals();
}

$response = $relay($request, new Response);
