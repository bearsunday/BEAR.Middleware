<?php

namespace BEAR\Middleware\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet()
    {
        $this['msg'] = 'hello world';

        return $this;
    }
}
