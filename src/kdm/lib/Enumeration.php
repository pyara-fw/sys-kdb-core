<?php

namespace  SysKDB\kdm\lib;

class Enumeration
{
    public const  UNKNOWN = 'uknown';

    /**
     *
     *
     * @var string
     */
    protected $value;



    /**
     * Get the value of value
     *
     * @return  string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the value of value
     *
     * @param  string  $value
     *
     * @return  self
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    public function __construct($value=null)
    {
        $this->value = $value;
    }

    /**
     * Generate an OID for the current object
     *
     * @return string
     */
    public function getOid()
    {
        return get_class($this) . '::' . $this->getValue();
    }
}
