<?php

namespace SysKDB\kdb\repository\adapter;

use SysKDB\kdb\exception\ObjectNotFoundException;
use SysKDB\kdb\repository\DataSet;

/**
 *
 */
interface AdapterInterface
{
    /**
     * Stores the record on DB and returns its ID.
     *
     * @param array $object
     * @return string Record ID
     */
    public function addObject(array $object): string;


    /**
     * Seek on DB by an object with the given $oid.
     * If don't find, throws an Exception
     *
     * @param string $oid
     * @return array
     * @throws ObjectNotFoundException
     */
    public function getObjectById(string $oid): array;


    /**
     * Remove the object with the given $oid.
     * If don't find, throws an Exception
     *
     * @param string $oid
     * @return void
     * @throws ObjectNotFoundException
     */
    public function removeObjectById(string $oid);


    /**
     * Update the object of the given $oid.
     *
     * @param string $oid
     * @param array $obj
     * @return void
     */
    public function updateObjectById(string $oid, array $object);


    /**
     * Get all objects
     *
     * @return DataSet
     */
    public function getAll(): DataSet;
}
