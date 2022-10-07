<?php

namespace SysKDB\lib\dao;

use InvalidArgumentException;
use SysKDB\lib\Constants;
use SysKDB\lib\UniqueId;

class PersistentObjectFactory
{
    public static function make(string $className, ?array $data = []): PersistentObject
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Invalid object. The informed classname does not exists - $className");
        }

        $obj = new $className();
        $obj->unpack($data);

        if (!$obj->getOid()) {
            $obj->setOid(UniqueId::get());
        }

        return $obj;
    }


    public static function makeFromSerialized($data): PersistentObject
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

        $obj = static::make($data[Constants::CLASSNAME], $data);
        return $obj;
    }
}
