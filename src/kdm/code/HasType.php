<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;
use SysKDB\kdm\lib\omg\mof\DataType;

/**
 * The HasType is a specific meta-model element that represents semantic relation
 * between a data element and the corresponding type element.
 */
class HasType extends AbstractCodeRelationship
{
    /**
     * the source data element
     *
     * @var CodeItem
     */
    protected $from;

    /**
     * the target datatype element
     *
     * @var DataType
     */
    protected $to;


    /**
     * @return  CodeItem
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param  CodeItem  $from
     *
     * @return  self
     */
    public function setFrom(KDMEntity $from)
    {
        if (is_a($from, CodeItem::class)) {
            $this->from = $from;
        }

        return $this;
    }

    /**
     * @return  DataType
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  DataType  $to  actual parameter to template instantiation
     *
     * @return  self
     */
    public function setTo(KDMEntity $to)
    {
        if (is_a($to, DataType::class)) {
            $this->to = $to;
        }

        return $this;
    }
}
