<?php
/**
 * This file is part of the BEAR.Middleware package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace __PACKAGE__\__VENDOR__;

use BEAR\AppMeta\AppMeta;
use BEAR\Middleware\Boot;
use BEAR\Middleware\Handler\ResourceHandler;
use BEAR\Middleware\Resolver;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Relay\Middleware\ExceptionHandler;
use Relay\Middleware\ResponseSender;
use Relay\RelayBuilder;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

loader: {
    $loader = require dirname(__DIR__) . '/vendor/autoload.php';
    AnnotationRegistry::registerLoader([$loader, 'loadClass']);
}
init: {
    $injector = (new Boot)->getInjector(new AppMeta(__NAMESPACE__), $context);
    $relayBuilder = new RelayBuilder(new Resolver($injector));
    $queue = [
        ResponseSender::class,
        ExceptionHandler::class,
        ResourceHandler::class
    ];
    $relay = $relayBuilder->newInstance($queue);
    $request = ServerRequestFactory::fromGlobals();
}

$relay($request, new Response);
