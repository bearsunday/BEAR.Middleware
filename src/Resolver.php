<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware;

use Ray\Di\InjectorInterface;

final class Resolver
{
    /**
     * @var InjectorInterface
     */
    protected $injector;

    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    public function __invoke($spec)
    {
        if (is_string($spec)) {
            return $this->injector->getInstance($spec);
        }

        if (is_array($spec) && is_string($spec[0])) {
            $spec[0] = $this->injector->getInstance($spec[0]);
        }

        return $spec;
    }
}
