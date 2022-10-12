<?php

namespace SysKDB\parser;


class TokenCondition extends Condition
{
    public const TYPE = 'token';

    public function evaluate($token)
    {
        if ($this->isEqual($token[0])) {
            return true;
        }
        return false;
    }
}
