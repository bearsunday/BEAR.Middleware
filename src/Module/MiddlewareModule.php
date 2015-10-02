<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Module;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Zend\Diactoros\Response;

class MiddlewareModule extends AbstractModule
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->bind(RequestInterface::class)->toProvider(RequestProvider::class)->in(Scope::SINGLETON);
        $this->bind(ResponseInterface::class)->to(Response::class)->in(Scope::SINGLETON);
    }
}
