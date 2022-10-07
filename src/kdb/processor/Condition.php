<?php

namespace SysKDB\kdb\processor;


interface Condition {
    public function getId() : string;
    public function evaluate($transition, $fsm, $token, $currentState) : bool;
}