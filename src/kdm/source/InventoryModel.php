<?php

namespace SysKDB\kdm\source;

use SysKDB\kdm\lib\AbstractInventoryElementList;

class InventoryModel
{
    /**
     *
     *
     * @var AbstractInventoryElementList
     */
    protected $inventoryElement;



    /**
     * Get the value of inventoryElement
     *
     * @return  AbstractInventoryElementList
     */
    public function getInventoryElement()
    {
        if (!$this->inventoryElement) {
            $this->inventoryElement = new AbstractInventoryElementList();
        }
        return $this->inventoryElement;
    }
}
