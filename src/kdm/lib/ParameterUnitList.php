<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\ParameterUnit;

class ParameterUnitList extends ListBase
{
    /**
     *
     *
     * @param ParameterUnit $element
     * @return void
     */
    public function add(ParameterUnit $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return ParameterUnit|null
     */
    public function get(int $i): ?ParameterUnit
    {
        return  $this->list[$i] ?? null;
    }
}
