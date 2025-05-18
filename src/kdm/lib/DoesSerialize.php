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
     * @return array
     */
    public function unserialize(string $str)
    {
        $arrValues = json_decode($str, true);
        $this->unpack($arrValues);
        return get_object_vars($this);
    }

    /**
     * @param array $data
     * @return self
     */
    public function unpack(array $data)
    {
        foreach ($data as $k=>$v) {
            $this->$k = $v;
        }
        return $this;
    }
}
