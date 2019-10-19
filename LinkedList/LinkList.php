<?php

namespace LinkList;

class Node
{
    public $data;

    public $next;

    public function __construct(int $data = -1)
    {
        $this->data = $data;
    }

}