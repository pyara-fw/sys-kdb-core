<?php

namespace SysKDB\lib\dao;

abstract class DataAccessObject
{
    abstract public function connect();
    abstract public function disconnect();

    abstract public function getObjectById(string $oid): ?PersistentObject;

    abstract public function storeObject(PersistentObject $bject): ?PersistentObject;

    abstract public function removeObjectById(string $oid): bool;
}
