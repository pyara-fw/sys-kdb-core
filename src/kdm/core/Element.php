<?php

namespace SysKDB\kdm\core;

use SysKDB\kdb\KDB;
use SysKDB\kdm\lib\Constants;
use SysKDB\kdm\lib\HasOID as LibHasOID;
use SysKDB\lib\dao\PersistentObject;
use SysKDB\kdm\lib\DoesCompare;
use SysKDB\kdm\lib\DoesSerialize;
use SysKDB\lib\Constants as LibConstants;

/**
 * @author Eduardo Luz <eduardo @ eduardo-luz.com>
 * @package sysKDB
 */
abstract class Element implements PersistentObject
{
    use LibHasOID;
    use DoesSerialize;
    use DoesCompare;

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    protected $internalProcessingStatus;

    protected $internalClassName;


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
        $this->internalClassName = get_class($this);
    }


    /**
     * Exports the object in an intermediate format, to be converted
     * to KDM later.
     *
     * @return array
     */
    public function export(): array
    {
        $result = [];
        $result[Constants::CLASS_NAME] = get_class($this);
        $result[Constants::OBJ_DATA] = get_object_vars($this);
        return $result;
    }


    public function getReferencedAttributesMap(): array
    {
        return [];
    }

    /**
     * Get the value of internalProcessingStatus
     */
    public function getProcessingStatus()
    {
        return $this->internalProcessingStatus;
    }

    /**
     * Set the value of processingStatus
     *
     * @return  self
     */
    public function setProcessingStatus($processingStatus)
    {
        $this->internalProcessingStatus = $processingStatus;
        return $this;
    }
}
