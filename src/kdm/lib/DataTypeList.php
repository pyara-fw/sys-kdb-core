<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\DataType;

class DataTypeList extends ListBase
{
    /**
     *
     *
     * @param DataType $element
     * @return void
     */
    public function add(DataType $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return DataType|null
     */
    public function get(int $i): ?DataType
    {
        return  $this->list[$i] ?? null;
    }
}
