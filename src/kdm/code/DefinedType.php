<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\DataTypeList;

/**
 * DefinedType element represents a named element of existing software system,
 * which corresponds to a user-defined datatype.
 */
abstract class DefinedType extends DataType
{
    /**
     *  Anonymous datatypes used in the definition of the datatype.
     *
     * @var DataTypeList
     */
    protected $codeElement;


    /**
     * The datatype of the DefinedType that describes the values of the corresponding datatype.
     *
     * @var DataType
     */
    protected $type;
}
