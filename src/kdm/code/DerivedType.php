<?php

namespace  SysKDB\kdm\code;

/**
 *
 */
class DerivedType extends DataType
{
    /**
     *
     *
     * @var ItemUnit
     */
    protected $itemUnit;

    /**
     * Get the value of itemUnit
     *
     * @return  ItemUnit
     */
    public function getItemUnit()
    {
        return $this->itemUnit;
    }

    /**
     * Set the value of itemUnit
     *
     * @param  ItemUnit  $itemUnit
     *
     * @return  self
     */
    public function setItemUnit(ItemUnit $itemUnit)
    {
        $this->itemUnit = $itemUnit;

        return $this;
    }
}
