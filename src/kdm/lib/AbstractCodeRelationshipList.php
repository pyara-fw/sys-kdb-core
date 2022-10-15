<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\AbstractCodeRelationship;
use SysKDB\kdm\core\KDMRelationship;

class AbstractCodeRelationshipList extends ListBase
{
    /**
     *
     *
     * @param AbstractCodeRelationship $element
     * @return void
     */
    public function add(KDMRelationship $element)
    {
        if (!$this->checkMapIfExists($element->getOid())) {
            array_push($this->list, $element);
        }
    }

    /**
     * @param integer $i
     * @return AbstractCodeRelationship|null
     */
    public function get(int $i): ?KDMRelationship
    {
        return  $this->list[$i] ?? null;
    }
}
