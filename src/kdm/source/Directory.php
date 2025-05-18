<?php

namespace  SysKDB\kdm\source;

use SysKDB\kdm\lib\HasPath;

/**
 * The Directory class represents directories as containers that own inventory items.
 */
class Directory extends InventoryContainer
{
    use HasPath;
}
