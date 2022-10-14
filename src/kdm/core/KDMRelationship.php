<?php

namespace SysKDB\kdm\core;

class KDMRelationship extends ModelElement
{
    /**
     * @var KDMEntity
     */
    protected $from;

    /**
     * @var KDMEntity
     */
    protected $to;



    /**
     * Get the value of from
     *
     * @return  KDMEntity
     */
    public function getFrom()
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
    public function setFrom(KDMEntity $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the value of to
     *
     * @return  KDMEntity
     */
    public function getTo()
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
    public function setTo(KDMEntity $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param KDMEntity $from
     * @param KDMEntity $to
     */
    public function __construct(KDMEntity $from=null, KDMEntity $to=null)
    {
        parent::__construct();

        if ($from) {
            $this->from = $from;
            $from->getOutbound()->add($this);
        }

        if ($to) {
            $this->to = $to;
            $to->getInbound()->add($this);
        }
    }
}
