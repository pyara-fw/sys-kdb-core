<?php

namespace SysKDB\kdb\processor;



class Transition {

    protected $initialState;
    protected $finalState;

    protected $condition;
    protected $action;

    public function __construct($initialState, $finalState, Condition $condition, $action=null)
    {
        $this->initialState = $initialState;
        $this->finalState = $finalState;
        $this->condition = $condition;
        $this->action = $action;
    }

    public function isActivated($fsm, $token, $currentState) {
        return ($this->condition->evaluate($this, $fsm, $token, $currentState));
    }
    public function advance($fsm, $token) {
        $fsm->deactivateState($this->initialState);
        $fsm->activateState($this->finalState);
        if ($this->action) {
            $this->action->call([
                KDB_TRANSITION => $this, 
                KDB_FSM =>  $fsm, 
                KDB_TOKEN => $token, 
                KDB_PREV_STATE => $this->initialState,
                KDB_NEW_STATE => $this->finalState
            ]);
        }
            
            
        
    }
}
