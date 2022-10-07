<?php

namespace SysKDB\lib\dao;

use SysKDB\lib\dao\drivers\Redis;
use SysKDB\lib\exception\InvalidArgumentException;

class DataAccessObjectFactory
{
    public static function make($driverName)
    {
        switch ($driverName) {
            case 'redis':
                return new Redis();
        }

        throw new InvalidArgumentException("Invalid persistence driver name: $driverName");
    }
}
