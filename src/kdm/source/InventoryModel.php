<?php

namespace SysKDB\kdm\source;

use SysKDB\kdm\kdm\KDMModel;
use SysKDB\kdm\lib\AbstractInventoryElementList;

class InventoryModel extends KDMModel
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
        if (!is_object($this->inventoryElement)) {
            $this->inventoryElement = new AbstractInventoryElementList();
        }
        return $this->inventoryElement;
    }
}
