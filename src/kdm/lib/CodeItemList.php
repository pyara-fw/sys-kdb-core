<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\CodeItem;

class CodeItemList extends ListBase
{
    /**
     *
     *
     * @param CodeItem $element
     * @return void
     */
    public function add(CodeItem $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return CodeItem|null
     */
    public function get(int $i): ?CodeItem
    {
        return  $this->list[$i] ?? null;
    }
}


//
