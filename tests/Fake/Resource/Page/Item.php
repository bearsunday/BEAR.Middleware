<?php

namespace BEAR\Middleware\Resource\Page;

use BEAR\Resource\ResourceObject;

class Item extends ResourceObject
{
    public function onGet()
    {
        $this['msg'] = 'hello world';
        $this['stream'] = fopen(__DIR__ . '/message.txt', 'r');

        return $this;
    }
}
