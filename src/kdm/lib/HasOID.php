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

    /**
     * Create a Object ID (oid) based on current instance
     * and a random value;
     *
     * @return void
     */
    protected function makeOid()
    {
        $preffix = get_class($this);
        $suffix = hash('SHA256', random_bytes(10));

        $this->oid = sprintf("%s::%s", $preffix, $suffix);
    }
}
