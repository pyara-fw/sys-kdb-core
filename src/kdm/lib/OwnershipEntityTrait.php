<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\core\KDMEntity;
use SysKDB\kdm\lib\KDMEntityList;

trait OwnershipEntityTrait
{
    /**
     * @var KDMEntityList
     */
    protected $ownedElements;


    /**
     * @var KDMEntity
     */
    protected $owner;


    /**
     * Get the value of owner
     *
     * @return  KDMEntity
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @param  KDMEntity  $owner
     *
     * @return  self
     */
    public function setOwner(KDMEntity $owner)
    {
        $this->owner = $owner;
        $owner->getOwnedElements()->add($this);
        return $this;
    }


    /**
     * @return KDMEntityList
     */
    public function getOwnedElements(): KDMEntityList
    {
        if (!$this->ownedElements) {
            $this->ownedElements = new KDMEntityList();
        }
        return $this->ownedElements;
    }
}
