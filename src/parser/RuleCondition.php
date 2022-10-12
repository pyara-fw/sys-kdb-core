<?php

namespace SysKDB\parser;

class RuleCondition extends Condition
{
    public const TYPE = 'rule';

    public function evaluate($token)
    {
        return eval('return ' .$this->condition . ';');
    }
}
