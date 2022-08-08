<?php

namespace SysKDB\lib;

trait DoesSerialize
{
    public function serialize(): string
    {
        $arrPack = get_object_vars($this);
        $arrPack[Constants::CLASSNAME] = static::class;
        return json_encode($arrPack);
    }

    public function unserialize(string $str)
    {
        $arrValues = json_decode($str, true);
        return $this->unpack($arrValues);
    }

    public function unpack(array $data)
    {
        foreach ($data as $k=>$v) {
            $this->$k = $v;
        }
        return $this;
    }
}
