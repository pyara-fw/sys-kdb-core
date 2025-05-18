<?php

namespace  SysKDB\kdm\source;

use SysKDB\kdm\lib\HasPath;

/**
 * InventoryItem is a generic meta-model element that represents any
 * artifact of an existing software system. This class is further
 * subclasses by several concrete meta-model elements with more precise semantics.
 * However, InventoryItem can be used as an extended modeling element with a
 * stereotype.
 *
 */
class InventoryItem extends AbstractInventoryElement
{
    use HasPath;


    /**
     * @var string
     */
    protected $version;


    /**
     * Get the value of version
     *
     * @return  string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set the value of version
     *
     * @param  string  $version
     *
     * @return  self
     */
    public function setVersion(string $version)
    {
        $this->version = $version;

        return $this;
    }
}
