<?php

namespace SysKDB\parser;


abstract class Condition
{
    public const TYPE = 'base';
    public $type;
    public $condition;

    public function isEqual($toCompare)
    {
        return ($this->condition == $toCompare);
    }

    public function evaluate($token)
    {
        if ($this->isEqual($token)) {
            return true;
        }
        return false;
    }

    public function __construct($condition)
    {
        $this->condition = $condition;
    }
}
