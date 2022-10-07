<?php

namespace SysKDB\kdm\core;

use SysKDB\kdb\KDB;
use SysKDB\lib\Constants;
use SysKDB\lib\dao\PersistentObject;
use SysKDB\lib\DoesCompare;
use SysKDB\lib\HasOID;
use SysKDB\lib\DoesSerialize;

/**
 * @author Eduardo Luz <eduardo @ eduardo-luz.com>
 * @package sysKDB
 */
abstract class Element implements PersistentObject
{
    use HasOID;
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
}
