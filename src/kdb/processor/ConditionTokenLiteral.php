<?php

namespace SysKDB\kdb\processor;


class ConditionTokenLiteral implements Condition{

    protected $token;

    public function __construct($token) 
    {
        $this->token = $token;
    }

    public function getId(): string {
        return sprintf("%s:%s",__CLASS__, $this->token);
    }

    public function evaluate($transition, $fsm, $token, $currentState) : bool {
        if (is_string($token)) {
            return ($token === $this->token);
        }
        return false;
    }
}
