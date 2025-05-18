<?php

namespace  SysKDB\kdm\code;

/**
 * StorableUnit class is a concrete subclass of the DataElement class
 * that represents variables of the existing software system.
 */
class StorableUnit extends DataElement
{
    /**
     * Optional attribute that specifies the common details of a StorableUnit
     * (see StorableKind enumeration datatype).
     *
     * @var StorableKind
     */
    protected $kind;
}
