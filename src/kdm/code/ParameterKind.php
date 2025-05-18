<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\Enumeration;

/**
 * ParameterKind datatype defines the kind of parameter passing conventions.
 */
class ParameterKind extends Enumeration
{
    /**
     * parameter is passed by value
     */
    public const BY_VALUE = 'byValue';

    /**
     * parameter is passed by name
     */
    public const BY_NAME = 'byName';

    /**
     * parameter is passed by reference
     */
    public const BY_REFERENCE = 'byReference';

    /**
     * parameter is variadic
     */
    public const VARIADIC = 'variadic';

    /**
     * parameter being returned
     */
    public const RETURN = 'return';

    /**
     * parameter represents an exception thrown by the procedure
     */
    public const THROWS = 'throws';

    /**
     * parameter to a catch block
     */
    public const EXCEPTION = 'exception';

    /**
     * special parameter to a catch block
     */
    public const CATCH_ALL = 'catchAll';
}
