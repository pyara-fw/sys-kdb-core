<?php

namespace  SysKDB\kdm\code;

/**
 * The PrimitiveTypes class diagram defines meta-model elements that represent
 * predefined types common to various programming languages.
 */
class PrimitiveType extends DataType
{
    protected static $instance;
    public static function getInstance()
    {
        if (!static::$instance) {
            $className = static::class;
            static::$instance = new $className();
        }
        return static::$instance;
    }

    public function __toString()
    {
        if (@isset($this->value)) {
            return $this->value;
        }
        return get_class($this);
    }
}
