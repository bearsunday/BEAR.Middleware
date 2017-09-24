<?php
/**
 * This file is part of the BEAR.Middleware package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Module;

use Ray\Di\ProviderInterface;
use Zend\Diactoros\ServerRequestFactory;

class RequestProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return ServerRequestFactory::fromGlobals();
    }
}
