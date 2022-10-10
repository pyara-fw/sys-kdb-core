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
     * @var DataType
     */
    protected $codeElement;


    /**
     * The datatype of the DefinedType that describes the values of the corresponding datatype.
     *
     * @var DataType
     */
    protected $type;

    /**
     * Get anonymous datatypes used in the definition of the datatype.
     *
     * @return  DataType
     */
    public function getCodeElement()
    {
        return $this->codeElement;
    }

    /**
     * Set anonymous datatypes used in the definition of the datatype.
     *
     * @param  DataType  $codeElement  Anonymous datatypes used in the definition of the datatype.
     *
     * @return  self
     */
    public function setCodeElement(DataType $codeElement)
    {
        $this->codeElement = $codeElement;

        return $this;
    }

    /**
     * Get the datatype of the DefinedType that describes the values of the corresponding datatype.
     *
     * @return  DataType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the datatype of the DefinedType that describes the values of the corresponding datatype.
     *
     * @param  DataType  $type  The datatype of the DefinedType that describes the values of the corresponding datatype.
     *
     * @return  self
     */
    public function setType(DataType $type)
    {
        $this->type = $type;

        return $this;
    }
}
