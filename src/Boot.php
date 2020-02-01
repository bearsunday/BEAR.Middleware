<?php
/**
 * This file is part of the BEAR.Middleware package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Middleware\Exception\InvalidContextException;
use BEAR\Middleware\Module\AppMetaModule;
use BEAR\Middleware\Module\MiddlewareModule;
use BEAR\Middleware\Module\StreamModule;
use BEAR\Package\AbstractAppModule;
use BEAR\Package\Exception\InvalidModuleException;
use Ray\Compiler\DiCompiler;
use Ray\Compiler\Exception\NotCompiled;
use Ray\Compiler\ScriptInjector;
use Ray\Di\AbstractModule;
use Ray\Di\InjectorInterface;
use Ray\Di\NullModule;

class Boot
{
    public function getInjector(AbstractAppMeta $appMeta, $contexts)
    {
        try {
            $injector = (new ScriptInjector($appMeta->tmpDir))->getInstance(InjectorInterface::class);
        } catch (NotCompiled $e) {
            $module = $this->getContxtualModule($appMeta, $contexts);
            $module->override(new MiddlewareModule(new AppMetaModule($appMeta)));
            $compiler = new DiCompiler(new StreamModule($module), $appMeta->tmpDir);
            $compiler->compile();
            $injector = (new ScriptInjector($appMeta->tmpDir))->getInstance(InjectorInterface::class);
        }

        return $injector;
    }

    /**
     * Return configured module
     *
     * @param AbstractAppMeta $appMeta
     * @param string          $contexts
     *
     * @return AbstractModule
     */
    private function getContxtualModule(AbstractAppMeta $appMeta, string $contexts)
    {
        $contextsArray = array_reverse(explode('-', $contexts));
        $module = new NullModule;
        foreach ($contextsArray as $contextItem) {
            $class = $appMeta->name . '\Module\\' . ucwords($contextItem) . 'Module';
            if (! class_exists($class)) {
                $class = 'BEAR\Package\Context\\' . ucwords($contextItem) . 'Module';
            }
            if (! is_a($class, AbstractModule::class, true)) {
                throw new \BEAR\Package\Exception\InvalidContextException($contextItem);
            }
            /* @var $module AbstractModule */
            $module = is_subclass_of($class, AbstractAppModule::class) ? new $class($appMeta, $module) : new $class($module);
        }
        if (! $module instanceof AbstractModule) {
            throw new InvalidModuleException; // @codeCoverageIgnore
        }
        $module->override(new AppMetaModule($appMeta));

        return $module;
    }
}
