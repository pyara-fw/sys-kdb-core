<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\source\AbstractInventoryRelationship;

class AbstractInventoryRelationshipList extends ListBase
{
    /**
     *
     *
     * @param AbstractInventoryRelationship $element
     * @return void
     */
    public function add(AbstractInventoryRelationship $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return AbstractInventoryRelationship|null
     */
    public function get(int $i): ?AbstractInventoryRelationship
    {
        return  $this->list[$i] ?? null;
    }
}
