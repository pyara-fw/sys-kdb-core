<?php

namespace SysKDB\parser;



class SimpleCondition extends Condition
{
    public const TYPE = 'simple';

    public function evaluate($token)
    {
        if ($this->isEqual($token)) {
            return true;
        }
        return false;
    }
}
