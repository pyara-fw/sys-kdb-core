<?php

namespace SysKDB\kdm\lib;

/**
 *
 */
trait HasOID
{
    /**
     * @var string
     */
    protected $oid;



    /**
     * Get the value of oid
     *
     * @return  string
     */
    public function getOid()
    {
        return $this->oid;
    }

    /**
     * Set the value of oid
     *
     * @param  string  $oid
     *
     * @return  self
     */
    public function setOid(string $oid)
    {
        $this->oid = $oid;

        return $this;
    }
}
