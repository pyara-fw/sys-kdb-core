<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\ItemUnitList;

/**
 * The CompositeType is a meta-model element that represents user-defined
 * composite datatypes, such as records, structures, and unions.
 */
class CompositeType extends DataType
{
    /**
     *
     *
     * @var ItemUnitList
     */
    protected $item;

    /**
     * Get the value of item
     *
     * @return  ItemUnitList
     */
    public function getItem()
    {
        if (!is_object($this->item)) {
            $this->item = new ItemUnitList();
        }
        return $this->item;
    }
}
