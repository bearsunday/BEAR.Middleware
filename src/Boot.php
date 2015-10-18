<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Middleware\Exception\InvalidContextException;
use BEAR\Middleware\Module\AppMetaModule;
use BEAR\Middleware\Module\StreamModule;
use Ray\Compiler\DiCompiler;
use Ray\Compiler\Exception\NotCompiled;
use Ray\Compiler\ScriptInjector;
use Ray\Di\AbstractModule;
use Ray\Di\InjectorInterface;

class Boot
{
    public function getInjector(AbstractAppMeta $appMeta, $contexts)
    {
        try {
            $injector = (new ScriptInjector($appMeta->tmpDir))->getInstance(InjectorInterface::class);
        } catch (NotCompiled $e) {
            $module = $this->getContxtualModule($appMeta, $contexts);
            $module->override(new AppMetaModule($appMeta));
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
    private function getContxtualModule(AbstractAppMeta $appMeta, $contexts)
    {
        $contextsArray = array_reverse(explode('-', $contexts));
        $module = null;
        foreach ($contextsArray as $context) {
            $class = $appMeta->name . '\Module\\' . ucwords($context) . 'Module';
            if (!class_exists($class)) {
                $class = 'BEAR\Package\Context\\' . ucwords($context) . 'Module';
            }
            if (! is_a($class, AbstractModule::class, true)) {
                throw new InvalidContextException($context);
            }
            $module = new $class($module);
        }

        return $module;
    }
}
