<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Module;

use BEAR\Resource\Annotation\AppName;
use BEAR\Sunday\Module\SundayModule;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

class MiddlewareModule extends AbstractModule
{
    /**
     * @var AbstractModule
     */
    private $appName;

    public function __construct($appName)
    {
        $this->appName = $appName;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->bind()->annotatedWith(AppName::class)->toInstance($this->appName);
        $this->bind(RequestInterface::class)->toProvider(RequestProvider::class)->in(Scope::SINGLETON);
        $this->bind(ResponseInterface::class)->to(Response::class)->in(Scope::SINGLETON);
        $this->install(new SundayModule);
    }
}
