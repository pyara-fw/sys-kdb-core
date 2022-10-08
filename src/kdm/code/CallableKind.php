<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\Enumeration;

class CallableKind extends Enumeration
{
    /**
     * specifies an external procedure (a prototype, definition is elsewhere)
     */
    public const EXTERNAL = 'external';

    /**
     * specifies a regular definition of a procedure or function
     */
    public const REGULAR = 'regular';

    /**
     * specifies a definition of an operator
     */
    public const OPERATOR = 'operator';

    /**
     * specifies a stored procedure in DataModel
     */
    public const STORED = 'stored';
}
