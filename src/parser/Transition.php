<?php

namespace SysKDB\parser;

class Transition
{
    public $code;
    public $origin;
    public $target;
    public $condition;
    public $preFunction;

    public $elseFunction;

    public function __construct($code, $origin=null, $target=null, $condition=null, $preFunction=null)
    {
        $this->code = $code;

        if ($origin) {
            $this->origin = $origin;
        }

        if ($target) {
            $this->target = $target;
        }

        if ($condition) {
            $this->condition = $condition;
        }

        if ($preFunction) {
            $this->preFunction = $preFunction;
        }
    }

    public function check($token) {
        if ($this->condition) {
            return $this->condition->evaluate($token);
        } else {
            return true; // ??
        }
        
    }

    public function setElse($fn) {
        $this->elseFunction = $fn;
    }
}
