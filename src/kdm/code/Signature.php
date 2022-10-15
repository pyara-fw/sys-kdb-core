<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\ParameterUnitList;

/**
 *
 */
class Signature extends DataType
{
    /**
     * @var ParameterUnitList
     */
    protected $parameterUnit;

    /**
     * Get the value of parameterUnit
     *
     * @return  ParameterUnitList
     */
    public function getParameterUnit()
    {
        if (!is_object($this->parameterUnit)) {
            $this->parameterUnit = new ParameterUnitList();
        }
        return $this->parameterUnit;
    }
}
