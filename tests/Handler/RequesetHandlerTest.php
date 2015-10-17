<?php

namespace BEAR\Middleware;

use BEAR\Resource\Exception\ResourceNotFoundException;
use BEAR\Resource\Module\ResourceModule;
use BEAR\Resource\ResourceInterface;
use BEAR\Sunday\Provide\Router\WebRouter;
use BEAR\Middleware\Handler\ResourceHandler;
use Ray\Di\Injector;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Uri;

class RequestHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $resource;

    private $stream;

    protected function setUp()
    {
        $injector = new Injector(new ResourceModule(__NAMESPACE__));
        $this->resource = $injector->getInstance(ResourceInterface::class);
        $this->stream = fopen("php://temp/", 'r+');
    }

    public function testMissingRoute()
    {
        $this->setExpectedException(ResourceNotFoundException::class);
        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withUri(new Uri('http://localhost/not_found'));
        $response = new Response;
        $requestHandler = new ResourceHandler($this->resource, new WebRouter('page://self'), $this->stream);
        $requestHandler($request, $response, function ($req, $resp) {
            $this->assertInstanceOf(Response::class, $resp);
        });
    }

    public function testRouteMatch()
    {
        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withUri(new Uri('http://localhost/'));
        $response = new Response;
        $requestHandler = new ResourceHandler($this->resource, new WebRouter('page://self'), $this->stream);
        $requestHandler($request, $response, function ($req, $resp) {
            $this->assertInstanceOf(Response::class, $resp);
        });
    }
}
