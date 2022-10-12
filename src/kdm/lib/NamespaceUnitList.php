<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\NamespaceUnit;

class NamespaceUnitList extends ListBase
{
    /**
     * @param NamespaceUnit $element
     * @return void
     */
    public function add(NamespaceUnit $element)
    {
        if (!$this->checkMapIfExists($element->getOid())) {
            array_push($this->list, $element);
        }
    }

    /**
     * @param integer $i
     * @return NamespaceUnit|null
     */
    public function get(int $i): ?NamespaceUnit
    {
        return  $this->list[$i] ?? null;
    }
}


//

// NamespaceUnitList
