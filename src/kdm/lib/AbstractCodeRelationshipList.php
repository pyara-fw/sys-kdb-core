<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\AbstractCodeRelationship;

class AbstractCodeRelationshipList extends ListBase
{
    /**
     *
     *
     * @param AbstractCodeRelationship $element
     * @return void
     */
    public function add(AbstractCodeRelationship $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return AbstractCodeRelationship|null
     */
    public function get(int $i): ?AbstractCodeRelationship
    {
        return  $this->list[$i] ?? null;
    }
}
