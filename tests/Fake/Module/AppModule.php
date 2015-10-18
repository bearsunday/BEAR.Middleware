<?php
namespace BEAR\Middleware\Module;

use BEAR\Sunday\Module\SundayModule;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->install(new SundayModule);
    }
}
