<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\kdm\Attribute;

class AttributeList extends ListBase
{
    /**
     * @param Attribute $element
     * @return void
     */
    public function add(Attribute $element)
    {
        if (!$this->checkMapIfExists($element->getOid())) {
            array_push($this->list, $element);
        }
    }

    /**
     * @param integer $i
     * @return Attribute|null
     */
    public function get(int $i): ?Attribute
    {
        return  $this->list[$i] ?? null;
    }
}
