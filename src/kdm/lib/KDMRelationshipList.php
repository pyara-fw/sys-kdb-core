<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\core\KDMRelationship;

/**
 * Undocumented class
 */
class KDMRelationshipList extends ListBase
{
    /**
     *
     *
     * @param KDMRelationship $relationship
     * @return void
     */
    public function add(KDMRelationship $relationship)
    {
        array_push($this->list, $relationship);
    }

    /**
     * @param integer $i
     * @return KDMRelationship|null
     */
    public function get(int $i): ?KDMRelationship
    {
        return  $this->list[$i] ?? null;
    }
}
