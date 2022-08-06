<?php

namespace SysKDB\kdm\core;

use SysKDB\kdm\ext\KDMEntityList;

/**
 *
 * A KDMEntity can be either an atomic element, a container for some KDMEntities, or
 * a group of some KDMEntities
 *
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
     *
     *
     * @var KDMEntityList
     */
    protected $group;



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

    /**
     *
     *
     * @return KDMEntityList
     */
    public function getGroup(): KDMEntityList
    {
        if (!$this->group) {
            $this->group = new KDMEntityList();
        }
        return $this->group;
    }
}
