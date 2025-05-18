<?php

namespace SysKDB\kdm\lib;

use SysKDB\lib\Constants;

trait DoesCompare
{
    public function equals($toCompare): bool
    {
        if (!method_exists($toCompare, 'compareAttributesWithMe')) {
            throw new \InvalidArgumentException('Object is not comparable');
        }
        $myAttributes = get_object_vars($this);
        return $toCompare->compareAttributesWithMe($myAttributes);
    }

    public function compareAttributesWithMe(array $attributes): bool
    {
        $myAttributes = get_object_vars($this);

        foreach ($myAttributes as $k => $v) {
            if ($k == Constants::CLASSNAME) {
                continue;
            }

            if (!array_key_exists($k, $attributes)) {
                return false;
            }

            if ($attributes[$k] != $v) {
                return false;
            }
        }
        return true;
    }
}
