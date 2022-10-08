<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\Enumeration;

/**
 * StorableKind enumeration data type defines several common properties
 * of a StorableUnit related to their life-cycle, visibility, and memory
 * type.
 */
class StorableKind extends Enumeration
{
    public const GLOBAL = 'global';

    public const  LOCAL = 'local';
    public const  STATIC = 'static';
    public const  EXTERNAL = 'external';
    public const  REGISTER = 'register';
}
