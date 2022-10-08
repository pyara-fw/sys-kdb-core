<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\Enumeration;

class MethodKind extends Enumeration
{
    /**
     * The MethodUnit represents a regular member function.
     */
    public const METHOD = 'method';

    /**
     * The MethodUnit represents a constructor.
     */
    public const CONSTRUCTOR = 'constructor';

    /**
     * The MethodUnit represents a destructor.
     */
    public const DESTRUCTOR = 'destructor';

    /**
     * The MethodUnit represents an operator.
     */
    public const OPERATOR = 'operator';

    /**
     * The MethodUnit represents a virtual method.
     */
    public const VIRTUAL = 'virtual';

    /**
     * The MethodUnit represents an abstract method or member of an Interface.
     */
    public const ABSTRACT = 'abstract';

    /**
     * The kind of the MethodUnit is none of the above.
     */
    public const UNKNOWN = 'unknown';
}
