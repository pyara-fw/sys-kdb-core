<?php

namespace  SysKDB\kdm\source;

use SysKDB\kdm\lib\AbstractInventoryElementList;

/**
 * The InventoryContainer meta-model element provides a container for
 * instances of InventoryItem elements.
 *
 */
class InventoryContainer extends AbstractInventoryElement
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

    /**
     * Set the value of inventoryElement
     *
     * @param  AbstractInventoryElementList  $inventoryElement
     *
     * @return  self
     */
    public function setInventoryElement(AbstractInventoryElementList $inventoryElement)
    {
        $this->inventoryElement = $inventoryElement;

        return $this;
    }
}
