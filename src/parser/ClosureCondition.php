<?php

namespace SysKDB\parser;

class ClosureCondition extends Condition
{
    public const TYPE = 'rule';

    public function evaluate($token)
    {
        $closure = $this->condition;
        return $closure($token);
    }
}


// ClosureCondition