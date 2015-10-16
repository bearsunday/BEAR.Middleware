<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Handler;

use BEAR\Resource\ResourceInterface;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;

final class ResourceHandler
{
    /**
     * @var ResourceInterface
     */
    private $resource;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(ResourceInterface $resource, RouterInterface $router)
    {
        $this->resource = $resource;
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public function __invoke(RequestInterface $request, Response $response, callable $next = null)
    {
        $response = $this->handle($request, $response);

        return $next($request, $response);
    }

    /**
     * @param RequestInterface $request
     * @param Response         $response
     *
     * @return Response
     */
    public function handle(ServerRequestInterface $request, Response $response)
    {
        $server = $request->getServerParams();
        $server['REQUEST_METHOD'] = $request->getMethod();
        $server['REQUEST_URI'] = $request->getUri()->getPath();
        $globals = [
            '_GET' => $request->getQueryParams(),
            '_POST' => $request->getParsedBody()
        ];
        $req = $this->router->match($globals, $server);
        $resourceObject = $this->resource->{$req->method}->uri($req->path)->withQuery($req->query)->eager->request();
        $response = $this->write($response, $resourceObject);

        return $response;
    }

    /**
     * @param Response       $response
     * @param ResourceObject $resourceObject
     *
     * @return Response
     */
    private function write(Response $response, ResourceObject $resourceObject)
    {
        $responseBody = (string) $resourceObject;
        $response = $response->withStatus($resourceObject->code);
        foreach ($resourceObject->headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }
        $response->getBody()->write($responseBody);

        return $response;
    }
}


