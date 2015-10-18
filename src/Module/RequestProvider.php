<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Module;

use Ray\Di\ProviderInterface;
use Zend\Diactoros\ServerRequestFactory;

class RequestProvider implements ProviderInterface
{
    /**
     * @inheritDoc
     */
    public function get()
    {
        return ServerRequestFactory::fromGlobals();
    }
}
