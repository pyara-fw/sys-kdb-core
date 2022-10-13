<?php

namespace SysKDB\kdb\repository;

use ArrayIterator;
use Iterator;
use SysKDB\kdb\exception\AdapterNotSetException;
use SysKDB\kdb\repository\adapter\AdapterInterface;

class DataSet implements \IteratorAggregate, QueryableInterface
{
    use Queryable;

    /**
     * Internal repository
     *
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


    /**
      *
      *
      * @param mixed $element
      * @return void
      */
    public function add($element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return mixed|null
     */
    public function get(int $i)
    {
        return  $this->list[$i] ?? null;
    }

    /**
     * @param integer $i
     * @param mixed $item
     * @return self
     */
    public function set(int $i, $item): self
    {
        $this->list[$i] = $item;
        return $this;
    }

    /**
     * @param mixed $key
     * @param array $item
     * @return self
     */
    public function update($key, $item): self
    {
        $this->list[$key] = $item;
        return $this;
    }


    /**
     * @param array $items
     */
    public function __construct(array $items=[])
    {
        $this->list = $items;
    }

    public function getDataSource(): DataSet
    {
        return new self($this->list);
    }
}
