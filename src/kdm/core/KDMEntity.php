<?php

namespace SysKDB\kdm\core;

use SysKDB\kdm\lib\KDMEntityList;
use SysKDB\kdm\lib\omg\mof\DataType;
use SysKDB\kdm\lib\OwnershipEntityTrait;
use SysKDB\kdm\lib\RelationshipEntityTrait;

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
    use OwnershipEntityTrait;
    use RelationshipEntityTrait;

    /**
     * Entity's name
     *
     * @var string
     */
    protected string $name;


    /**
     * @var KDMEntityList
     */
    protected ?KDMEntityList $group = null;



    /**
     * Get entity's name
     *
     * @return  string
     */
    public function getName(): string
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
    public function setName(string $name): self
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Entity name cannot be empty');
        }
        if (strlen($name) > 255) {
            throw new \InvalidArgumentException('Entity name cannot be longer than 255 characters');
        }
    
        $this->name = $name;

        return $this;
    }

    /**
     *
     *
     * @return KDMEntityList
     */
    public function getGroup(): ?KDMEntityList
    {
        if (!$this->group) {
            $this->group = new KDMEntityList();
        }
        return $this->group;
    }
}
