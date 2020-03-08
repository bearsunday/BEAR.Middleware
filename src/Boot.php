<?php
/**
 * This file is part of the BEAR.Middleware package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Middleware\Module\AppMetaModule;
use BEAR\Middleware\Module\MiddlewareModule;
use BEAR\Middleware\Module\StreamModule;
use BEAR\Package\Module;
use Ray\Compiler\DiCompiler;
use Ray\Compiler\Exception\NotCompiled;
use Ray\Compiler\ScriptInjector;
use Ray\Di\InjectorInterface;

class Boot
{
    public function getInjector(AbstractAppMeta $appMeta, $contexts)
    {
        try {
            $injector = (new ScriptInjector($appMeta->tmpDir))->getInstance(InjectorInterface::class);
        } catch (NotCompiled $e) {
            $module = (new Module)($appMeta, $contexts);
            $module->override(new MiddlewareModule(new AppMetaModule($appMeta)));
            $compiler = new DiCompiler(new StreamModule($module), $appMeta->tmpDir);
            $compiler->compile();
            $injector = (new ScriptInjector($appMeta->tmpDir))->getInstance(InjectorInterface::class);
        }

        return $injector;
    }
}
