<?php

namespace Relay\Middleware;

use BEAR\Middleware\BootstrapTest;

function header($args)
{
    BootstrapTest::$headerArgs = $args;
}
