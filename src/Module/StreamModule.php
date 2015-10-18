<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Module;

use BEAR\Middleware\Annotation\Stream;
use BEAR\Resource\RenderInterface;
use BEAR\Sunday\Annotation\DefaultSchemeHost;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class StreamModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->rename(RenderInterface::class, 'original');
        $this->bind(RenderInterface::class)->to(StreamRenderer::class)->in(Scope::SINGLETON);
        $this->bind()->annotatedWith(Stream::class)->toProvider(StreamProvider::class)->in(Scope::SINGLETON);
    }
}
