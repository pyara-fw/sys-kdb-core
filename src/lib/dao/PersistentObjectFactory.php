<?php

namespace SysKDB\lib\dao;

use InvalidArgumentException;
use SysKDB\lib\Constants;

class PersistentObjectFactory
{
    public static function make($data): PersistentObject
    {
        switch (gettype($data)) {
            case 'string':
                return static::makeFromString($data);
            case 'array':
                return static::makeFromArray($data);
        }
    }

    protected static function makeFromString(string $data): PersistentObject
    {
        $unserializedData = json_decode($data, true);
        if (!is_array($unserializedData)) {
            throw new InvalidArgumentException("Invalid data string. It is not possible to unserialize it.");
        }

        return static::makeFromArray($unserializedData);
    }


    protected static function makeFromArray(array $data): PersistentObject
    {
        if (!isset($data[Constants::CLASSNAME])) {
            throw new InvalidArgumentException("Invalid data array. There is no classname informed");
        }

        $className = $data[Constants::CLASSNAME];

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Invalid object. The informed classname does not exists");
        }

        $obj = new $className();
        $obj->unpack($data);
        return $obj;
    }
}
