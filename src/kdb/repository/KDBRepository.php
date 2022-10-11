<?php

namespace SysKDB\kdb\repository;

use SysKDB\kdb\exception\AdapterNotSetException;
use SysKDB\kdb\repository\adapter\AdapterInterface;

class KDBRepository
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     * @return self
     */
    public function setAdapter(AdapterInterface $adapter): self
    {
        $this->adapter = $adapter;
        return $this;
    }


    /**
     * Get the value of adapter
     *
     * @return  AdapterInterface
     */
    public function getAdapter()
    {
        if (!$this->adapter) {
            throw new AdapterNotSetException("Adapter is not set");
        }
        return $this->adapter;
    }

    /**
     * Undocumented function
     *
     * @param array $records
     * @return void
     */
    public function import(array $records)
    {
        foreach ($records as $record) {
            $this->getAdapter()->addObject($record);
        }
    }
}
