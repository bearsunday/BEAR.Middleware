<?php

namespace BEAR\Middleware\Resource\Page;

use BEAR\Resource\ResourceObject;

class Greeting extends ResourceObject
{
    public function onGet()
    {
        $this->body['greeting'] = 'Hello BEAR';

        return $this;
    }
}
