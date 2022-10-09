<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\ItemUnit;

class ItemUnitList extends ListBase
{
    /**
     *
     *
     * @param ItemUnit $element
     * @return void
     */
    public function add(ItemUnit $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return ItemUnit|null
     */
    public function get(int $i): ?ItemUnit
    {
        return  $this->list[$i] ?? null;
    }
}
