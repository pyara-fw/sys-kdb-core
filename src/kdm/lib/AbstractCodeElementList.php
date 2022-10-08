<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\AbstractCodeElement;

class AbstractCodeElementList extends ListBase
{
    /**
     *
     *
     * @param AbstractCodeElement $element
     * @return void
     */
    public function add(AbstractCodeElement $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return AbstractCodeElement|null
     */
    public function get(int $i): ?AbstractCodeElement
    {
        return  $this->list[$i] ?? null;
    }
}
