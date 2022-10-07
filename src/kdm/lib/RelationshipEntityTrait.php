<?php

namespace SysKDB\kdm\lib;

// use SysKDB\kdm\lib\KDMRelationshipList;

trait RelationshipEntityTrait
{
    /**
     * @var KDMRelationshipList
     */
    protected $inbound;


    /**
     * @var KDMRelationshipList
     */
    protected $outbound;



    /**
     * @return KDMRelationshipList
     */
    public function getInbound(): KDMRelationshipList
    {
        if (!$this->inbound) {
            $this->inbound = new KDMRelationshipList();
        }
        return $this->inbound;
    }

    /**
     * @return KDMRelationshipList
     */
    public function getOutbound(): KDMRelationshipList
    {
        if (!$this->outbound) {
            $this->outbound = new KDMRelationshipList();
        }
        return $this->outbound;
    }
}
