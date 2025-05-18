<?php

namespace  SysKDB\kdm\code;

/**
 * ParameterUnit class is a concrete subclass of the DataElement class that
 * represents a formal parameter; for example, a formal parameter of a procedure.
 * ParameterUnits are owned by the Signature element.
 */
class ParameterUnit extends DataElement
{
    /**
     * optional attribute defining the parameter passing convention for the attribute
     *
     * @var ParameterKind
     */
    protected $kind;

    /**
     * position of the attribute in the signature
     *
     * @var int
     */
    protected $pos;
}
