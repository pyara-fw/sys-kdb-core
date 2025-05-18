<?php

namespace SysKDB\kdb\processor;


class ConditionTokenId implements Condition{

    protected $tokenId;

    public function __construct($tokenId) 
    {
        $this->tokenId = $tokenId;
    }

    public function getId(): string {
        return sprintf("%s:%s",__CLASS__, $this->tokenId);
    }

    public function evaluate($transition, $fsm, $token, $currentState) : bool {
        if (is_array($token) && isset($token[0])) {
            return ($token[0] === $this->tokenId);
        }
        return false;
    }
}
