<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\ValueElement;

class ValueElementList extends ListBase
{
    /**
     *
     *
     * @param ValueElement $element
     * @return void
     */
    public function add(ValueElement $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return ValueElement|null
     */
    public function get(int $i): ?ValueElement
    {
        return  $this->list[$i] ?? null;
    }
}
