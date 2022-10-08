<?php

namespace SysKDB\kdm\core;

use SysKDB\kdb\KDB;
use SysKDB\kdm\lib\HasOID as LibHasOID;
use SysKDB\lib\dao\PersistentObject;
use SysKDB\kdm\lib\DoesCompare;
use SysKDB\kdm\lib\DoesSerialize;

/**
 * @author Eduardo Luz <eduardo @ eduardo-luz.com>
 * @package sysKDB
 */
abstract class Element implements PersistentObject
{
    use LibHasOID;
    use DoesSerialize;
    use DoesCompare;


    public function store(): PersistentObject
    {
        return KDB::getInstance()->getDB()->storeObject($this);
    }
    public function remove(): bool
    {
        return KDB::getInstance()->getDB()->removeObjectById($this->getOid());
    }

    public function __construct()
    {
        $this->makeOid();
    }
}
