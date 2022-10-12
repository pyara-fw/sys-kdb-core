<?php

namespace SysKDB\parser;

class Parser
{
    public $states = [];

    public $initialStates = [];
    public $currentStates = [];
    public $transitions = [];

    public $mapTransitions = [];

    public $preFunction;

    public $vars = [];

    public function buildTransition($origin, $target, $condition, $preFunction=null)
    {
        if (is_null($origin)) {
            $originCode = null;
        } else {
            $originCode = $origin->code;
        }
        

        $code = hash('sha256', random_bytes(10));
        $t = new Transition($code, $originCode, $target->code, $condition, $preFunction);

        $this->transitions[$code] = $t;
        if (!isset($this->mapTransitions[$originCode])) {
            $this->mapTransitions[$originCode] = [];
        }
        $this->mapTransitions[$originCode][] = $code;

        return $this->transitions[$code];
    }

    public function process($token) {        

        $this->processState($this->currentStates, $token);
        $this->processInitialState( $token);
    }


    protected function processInitialState( $token) {
        foreach ($this->transitions as $k => $transition) {
            if (is_null($transition->origin)) {
                $isToAdvance = $transition->check($token);
                if ($isToAdvance) {
                    $state = new State(null);
                    if ($transition->preFunction) {
                        $fn = $transition->preFunction;
                        $data = $fn($state, $token, $this);
                    }
                    $nextState = clone $this->states[$transition->target];
                    $nextState->data = $data;
                    $this->currentStates[] = $nextState;
                }            
            }
        }        
    }



    protected function processInitialState_( $token) {
        $states = $this->initialStates;
        foreach ($states as $iState => $state) {            
            $code = $state->code;
            if (isset($this->mapTransitions[$code])) {
                foreach ($this->mapTransitions[$code] as $transitionCode) {
                    $transition = $this->transitions[$transitionCode];
                    $isToAdvance = $transition->check($token);
                    if ($isToAdvance) {
                        $data = $states[$iState]->data;
                        if ($transition->preFunction) {
                            $fn = $transition->preFunction;
                            $data = $fn($states[$iState], $token, $this);
                        }
                        $nextState = clone $this->states[$transition->target];
                        $nextState->data = $data;
                        $this->currentStates[] = $nextState;
                    // } else if ($transition->elseFunction) {
                    //     $fn = $transition->elseFunction;
                    //     $fn($states[$iState], $token, $this);
                    //     unset($states[$iState]);
                    }
                }
            }
        }
    }

    protected function processState(&$states, $token) {
        foreach ($states as $iState => $state) {            
            $code = $state->code;
            if (isset($this->mapTransitions[$code])) {
                foreach ($this->mapTransitions[$code] as $transitionCode) {
                    $transition = $this->transitions[$transitionCode];
                    $isToAdvance = $transition->check($token);
                    if ($isToAdvance) {
                        $data = $states[$iState]->data;
                        if ($transition->preFunction) {
                            $fn = $transition->preFunction;
                            $data = $fn($states[$iState], $token, $this);
                        }
                        $nextState = clone $this->states[$transition->target];
                        $states[$iState] = $nextState;
                        $states[$iState]->data = $data;
                        break;
                    } else {
                        
                        if ($transition->elseFunction) {
                            $fn = $transition->elseFunction;
                            $fn($states[$iState], $token, $this);
                            unset($states[$iState]);
                            break;
                        }
                    }
                }
            }
        }
    }


    public function addInitialState($state) {
        // $this->initialStates[] = $state;
        // $this->addState($state);
        return $state;
    }


    public function addInitialState_($state) {
        $this->initialStates[] = $state;
        $this->addState($state);
        return $state;
    }

    public function getInitialState() {
        return $this->initialStates[0];
    }


    public function addState($state) {
        $code = $state->code;
        $this->states[$code] = $state;
        return $state;
    }

    public function storeVar($name, $value) {
        $this->vars[$name] = $value;
        return $this;
    }

    public function getAllVars() {
        return $this->vars;
    }

    public function getVar($name, $default=null) {
        if (array_key_exists($name, $this->vars)) {
            return $this->vars[$name];
        }
        return $default;
    }
}
