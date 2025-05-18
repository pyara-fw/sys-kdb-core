<?php

namespace SysKDB\lib;

class UniqueId
{
    public static function get()
    {
        return uniqid('kdm-', true);
    }
}
