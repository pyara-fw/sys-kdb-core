<?php

namespace SysKDB\kdb\processor;

define('KDB_TRANSITION', 'transition');
define('KDB_FSM', 'fsm');
define('KDB_PREV_STATE', 'prev_state');
define('KDB_NEW_STATE', 'new_state');
define('KDB_TOKEN', 'token');

abstract class ProcessorBase
{
    public const INITIAL_STATE = 'INITIAL_STATE';

    public const VAR_DECLARED_CLASSES = 'declared_classes';
    public const VAR_DECLARED_CLASSES_NAMES = 'declared_class_names';
    public const VAR_DECLARED_FUNCTION_NAMES = 'declared_function_names';
    public const VAR_CURRENT_CLASS_NAME = 'current_class_name';
    public const VAR_BRACKETS = 'brackets';
    public const VAR_DECLARED_FUNCTIONS = 'declared_functions';
    public const VAR_METHODS = 'methods';
    public const VAR_INCLUDES = 'includes';


    protected $transitions = [];
    protected $activeStates = [];

    protected $vars = [];
    protected $arrays = [];

    protected $hashMap = [];



    public function setVar($name, $value)
    {
        $this->vars[$name] = $value;
        return $this;
    }

    public function getVar($name, $default=null)
    {
        return $this->vars[$name] ?? $default;
    }

    public function processTokens(array $contents)
    {
        foreach ($contents as $token) {
            $this->evaluate($token);
        }
    }

    public function evaluate($token)
    {
        $this->evaluateActiveStates($token);
        $this->evaluateInitialStates($token);
    }


    public function evaluateInitialStates($token)
    {
        if (!isset($this->transitions[self::INITIAL_STATE])) {
            throw new \Exception("Error - no initial status");
        }
        $possibleTransitions = $this->transitions[self::INITIAL_STATE];
        $this->evaluatePossibleTransitions($token, $possibleTransitions, self::INITIAL_STATE);
    }

    protected function evaluatePossibleTransitions($token, $possibleTransitions, $currentState)
    {
        foreach ($possibleTransitions as $transition) {
            if ($transition->isActivated($this, $token, $currentState)) {
                $transition->advance($this, $token);
            }
        }
    }

    public function evaluateActiveStates($token)
    {
        foreach ($this->transitions as $initialState => $list) {
            if ($initialState === self::INITIAL_STATE) {
                continue;
            }
            if (isset($this->activeStates[$initialState]) && $this->activeStates[$initialState]) {
                // $possibleTransitions = array_merge($possibleTransitions, $list);
                $this->evaluatePossibleTransitions($token, $list, $initialState);
            }
        }
    }


    public function deactivateState($state)
    {
        if ($state != self::INITIAL_STATE) {
            $this->activeStates[$state] = false;
        }
    }

    public function activateState($state)
    {
        if (!is_null($state)) {
            $this->activeStates[$state] = true;
        }
    }

    public function isActiveState($state)
    {
        return true === @$this->activeStates[$state];
    }

    public function getArray($name, $default = null)
    {
        return $this->arrays[$name] ?? $default;
    }

    public function pushArray($name, $value)
    {
        if (!isset($this->arrays[$name])) {
            $this->arrays[$name] = [];
        }
        $this->arrays[$name][] = $value;
        return $this;
    }

    public function hashSet($group, $hash, $value)
    {
        if (!isset($this->hashMap[$group])) {
            $this->hashMap[$group] = [];
        }
        $this->hashMap[$group][$hash] = $value;
        return $this;
    }

    public function hashGet($group, $hash, $default=null)
    {
        if (!isset($this->hashMap[$group])) {
            return $default;
        }
        if (!isset($this->hashMap[$group][$hash])) {
            return $default;
        }
        return $this->hashMap[$group][$hash];
    }



    public function add($initialState, $finalState, Condition $condition, $action=null)
    {
        if (!isset($this->transitions[$initialState])) {
            $this->transitions[$initialState] = [];
        }
        $transition = new Transition($initialState, $finalState, $condition, $action);
        // $conditionId = $condition->getId();
        // $target = $finalState .':'.$conditionId;
        $this->transitions[$initialState][] = $transition;
    }


    abstract public function parse($contents);



    public function getArrayDeclaredClassNames(): array
    {
        return $this->getArray(static::VAR_DECLARED_CLASSES_NAMES, []);
    }

    public function getArrayDeclaredFunctionNames(): array
    {
        return $this->getArray(static::VAR_DECLARED_FUNCTION_NAMES, []);
    }

    public function getAssocClass($className)
    {
        return $this->hashGet(static::VAR_DECLARED_CLASSES, $className);
    }

    public function getAssocFunction($functionName)
    {
        return $this->hashGet(static::VAR_DECLARED_FUNCTIONS, $functionName);
    }


    /**
     * @param string $contents
     * @return void
     */
    public function parseAndProcess(string $contents)
    {
        $tokens = $this->parse($contents);
        $this->processTokens($tokens);
    }


    /**
     * @return array
     */
    public function getClasses(): array
    {
        $result = [];

        $lsClasses = $this->getArrayDeclaredClassNames();

        foreach ($lsClasses as $className) {
            $arr = $this->getAssocClass($className);
            $key = $arr['namespace'] . '\\' . $className;
            $result[$key] = $arr;
        }

        return $result;
    }
}
