<?php

namespace SysKDB\kdm\core;

class KDMRelationship extends ModelElement
{
    /**
     * @var KDMEntity
     */
    protected KDMEntity $from;

    /**
     * @var KDMEntity
     */
    protected KDMEntity $to;



    /**
     * Get the value of from
     *
     * @return  KDMEntity
     */
    public function getFrom(): KDMEntity
    {
        return $this->from;
    }

    /**
     * Set the value of from
     *
     * @param  KDMEntity  $from
     *
     * @return  self
     */
    public function setFrom(KDMEntity $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the value of to
     *
     * @return  KDMEntity
     */
    public function getTo(): KDMEntity
    {
        return $this->to;
    }

    /**
     * Set the value of to
     *
     * @param  KDMEntity  $to
     *
     * @return  self
     */
    public function setTo(KDMEntity $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param KDMEntity $from
     * @param KDMEntity $to
     */
    public function __construct(KDMEntity $from, KDMEntity $to)
    {
        parent::__construct();

        $this->from = $from;
        $this->to = $to;

        $from->getOutbound()->add($this);
        $to->getInbound()->add($this);
    }
}
