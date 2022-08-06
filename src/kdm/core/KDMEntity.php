<?php

namespace SysKDB\kdm\core;

/**
 * @author Eduardo Luz <eduardo @ eduardo-luz.com>
 * @package sysKDB
 */
class KDMEntity extends ModelElement
{
    /**
     * Entity's name
     *
     * @var String
     */
    protected $name;




    /**
     * Get entity's name
     *
     * @return  String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set entity's name
     *
     * @param  String  $name  Entity's name
     *
     * @return  self
     */
    public function setName(String $name)
    {
        $this->name = $name;

        return $this;
    }
}
