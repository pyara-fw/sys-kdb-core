<?php

namespace SysKDB\kdm\lib;

use ArrayIterator;
use Iterator;
use IteratorAggregate;

class ListBase implements IteratorAggregate
{
    /**
     * @var array
     */
    protected $list = [];

    /**
     * Return the iterator as array
     *
     * @return Iterator
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->list);
    }

    /**
     * Remove item index $i from current list
     *
     * @param integer $i
     * @return void
     */
    public function remove(int $i)
    {
        $response = null;
        if (isset($this->list[$i])) {
            $response = $this->list[$i];
            unset($this->list[$i]);
        }
        return $response;
    }

    /**
     * Return the list of value.
     *
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }
}
