<?php

namespace SysKDB\kdm\lib;

use ArrayIterator;
use Iterator;
use IteratorAggregate;
use SysKDB\kdm\core\KDMEntity;

/**
 * Undocumented class
 */
class KDMEntityList implements IteratorAggregate
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
     * @param KDMEntity $entity
     * @return void
     */
    public function add(KDMEntity $entity)
    {
        array_push($this->list, $entity);
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
