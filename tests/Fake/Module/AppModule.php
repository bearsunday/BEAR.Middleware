<?php
namespace BEAR\Middleware\Module;

use BEAR\Resource\Module\ResourceModule;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->install(new ResourceModule('BEAR\Middleware'));
    }
}
