<?php

namespace SysKDB\kdb;

use SysKDB\lib\dao\DataAccessObject;

class KDB
{
    /**
     * @var self
     */
    protected static $instance;

    /**
     * Database
     *
     * @var DataAccessObject
     */
    protected $db;

    public static function getInstance(): self
    {
        if (!static::$instance) {
            static::$instance = new KDB();
        }
        return static::$instance;
    }



    /**
     * Get database
     *
     * @return  DataAccessObject
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * Set database
     *
     * @param  DataAccessObject  $db  Database
     *
     * @return  self
     */
    public function setDB(DataAccessObject $db)
    {
        $this->db = $db;

        return $this;
    }
}
