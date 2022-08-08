<?php

namespace SysKDB\kdm\core;

use SysKDB\lib\dao\PersistentObject;
use SysKDB\lib\HasOID;

/**
 * @author Eduardo Luz <eduardo @ eduardo-luz.com>
 * @package sysKDB
 */
abstract class Element implements PersistentObject
{
    use HasOID;

    public function serialize(): string
    {
        return json_encode(get_object_vars($this));
    }

    public function unserialize(string $str)
    {
        $arrValues = json_decode($str, true);
        foreach ($arrValues as $k=>$v) {
            $this->$k = $v;
        }
    }
}
