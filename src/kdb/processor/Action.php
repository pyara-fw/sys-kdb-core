<?php

namespace SysKDB\kdb\processor;


class Action {
    protected $fn;

    public function __construct($fn)
    {
        $this->fn = $fn;
    }

    public function call(...$parms) {
        call_user_func_array($this->fn, $parms);
    }
}
