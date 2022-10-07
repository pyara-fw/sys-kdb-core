<?php

namespace SysKDB\kdm\lib;

use SysKDB\lib\Constants;

/**
 *
 */
trait DoesSerialize
{
    /**
     * @return string
     */
    public function serialize(): string
    {
        $arrPack = get_object_vars($this);
        $arrPack[Constants::CLASSNAME] = static::class;
        return json_encode($arrPack);
    }

    /**
     * @param string $str
     * @return void
     */
    public function unserialize(string $str)
    {
        $arrValues = json_decode($str, true);
        return $this->unpack($arrValues);
    }

    /**
     * @param array $data
     * @return void
     */
    public function unpack(array $data)
    {
        foreach ($data as $k=>$v) {
            $this->$k = $v;
        }
        return $this;
    }
}
