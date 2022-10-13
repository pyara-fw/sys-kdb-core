<?php

namespace SysKDB\kdm\lib;

use SysKDB\lib\Constants;

/**
 *
 */
trait DoesSerialize
{
    protected static $ATTR_LIST;
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
        $this->import($arrValues);
        return get_object_vars($this);
    }

    /**
     * @param array $data
     * @param \Closure $data
     * @return mixed|null
     */
    public function import(array $data, \Closure $callback=null)
    {
        if (!static::$ATTR_LIST) {
            static::$ATTR_LIST = get_object_vars($this);
        }

        foreach ($data as $k=>$v) {
            if (array_key_exists($k, static::$ATTR_LIST)) {
                if (is_scalar($v)) {
                    $this->$k = $v;
                }
            }
        }

        if ($callback) {
            return $callback($this);
        }
    }

    public function exportVars(): array
    {
        return get_object_vars($this);
    }
}
