<?php

namespace SysKDB\lib;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use SysKDB\kdm\core\KDMRelationship;

/**
 * Undocumented class
 */
class KDMRelationshipList implements IteratorAggregate
{
    /**
     * @var array
     */
    protected $list = [];

    /**
     *
     *
     * @return Iterator
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->list);
    }

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
     * Remove the item on position $i
     *
     * @param integer $i
     * @return void
     */
    public function remove(int $i)
    {
        if (isset($this->list[$i])) {
            unset($this->list[$i]);
        }
    }
}
