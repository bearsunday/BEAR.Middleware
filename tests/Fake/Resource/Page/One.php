<?php

namespace BEAR\Middleware\Resource\Page;

use BEAR\Resource\ResourceObject;

class One extends ResourceObject
{
    /**
     * Ignore renderer, just stream $this->body
     */
    public function onGet()
    {
        $this->body = fopen(__DIR__ . '/message.txt', 'r');

        return $this;
    }
}
