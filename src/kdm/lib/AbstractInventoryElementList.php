<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\source\AbstractInventoryElement;

class AbstractInventoryElementList extends ListBase
{
    /**
     *
     *
     * @param AbstractInventoryElement $element
     * @return void
     */
    public function add(AbstractInventoryElement $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return AbstractInventoryElement|null
     */
    public function get(int $i): ?AbstractInventoryElement
    {
        return  $this->list[$i] ?? null;
    }
}
