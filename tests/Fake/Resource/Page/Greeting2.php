<?php

namespace BEAR\Middleware\Resource\Page;

use BEAR\Resource\ResourceObject;

class Greeting2 extends ResourceObject
{
    public function onGet()
    {
        $this->body = 'Hello BEAR';

        return $this;
    }
}
