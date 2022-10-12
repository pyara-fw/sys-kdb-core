<?php

namespace SysKDB\parser;


class State
{
    public $code;

    public $data = [];

    public function __construct($code)
    {
        $this->code = $code;
    }
}
