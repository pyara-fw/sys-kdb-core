<?php

namespace SysKDB\kdb\repository\util;

use SysKDB\kdb\repository\DataSet;
use SysKDB\kdm\core\Element;

class ConvertUtil
{
    public static function convertKDM_2_KDB(Element $element, string $version='1'): array
    {
        return KDM2KDBUtil::convert($element, $version);
    }

    public static function convertKDB_2_KDM(DataSet $list): array
    {
        return KDB2KDMUtil::convert($list);
    }

    public static function reset()
    {
        KDB2KDMUtil::reset();
        KDM2KDBUtil::reset();
    }
}
