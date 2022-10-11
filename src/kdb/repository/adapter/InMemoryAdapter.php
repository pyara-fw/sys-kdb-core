<?php

namespace SysKDB\kdb\repository\adapter;

use SysKDB\kdb\exception\ObjectNotFoundException;
use SysKDB\kdb\repository\DataSet;
use SysKDB\lib\Constants;

class InMemoryAdapter implements AdapterInterface
{
    /**
     * Internal storage
     *
     * @var array
     */
    protected $list = [];

    /**
     * Stores the record on DB and returns its ID.
     *
     * @param array $object
     * @return string Record ID
     */
    public function addObject(array $object): string
    {
        $oid = $object[Constants::OID] ?? '';
        $this->list[$oid] = $object;
        return $oid;
    }


    /**
     * Seek on DB by an object with the given $oid.
     * If don't find, throws an Exception
     *
     * @param string $oid
     * @return array
     * @throws ObjectNotFoundException
     */
    public function getObjectById(string $oid): array
    {
        if (!isset($this->list[$oid])) {
            throw new ObjectNotFoundException("Object not found with OID $oid");
        }

        $obj = $this->list[$oid];
        return $obj;
    }


    /**
     * Remove the object with the given $oid.
     * If don't find, throws an Exception
     *
     * @param string $oid
     * @return void
     * @throws ObjectNotFoundException
     */
    public function removeObjectById(string $oid)
    {
        if (!isset($this->list[$oid])) {
            throw new ObjectNotFoundException("Object not found with OID $oid");
        }
        unset($this->list[$oid]);
    }


    /**
     * Update the object of the given $oid.
     *
     * @param string $oid
     * @param array $obj
     * @return void
     */
    public function updateObjectById(string $oid, array $object)
    {
        $oid = $object[Constants::OID] ?? '';
        $this->list[$oid] = $object;
    }


    /**
     * Get all objects
     *
     * @return DataSet
     */
    public function getAll(): DataSet
    {
        $ds = new DataSet($this->list);
        return $ds;
    }
}
