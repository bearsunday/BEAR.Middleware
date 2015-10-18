<?php
/**
 * This file is part of the BEAR.MiddleWare package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Middleware\Annotation;

use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
final class Stream
{
    public $value;
}
