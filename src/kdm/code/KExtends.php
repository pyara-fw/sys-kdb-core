<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;
use SysKDB\kdm\lib\omg\mof\DataType;

/**
 * The Extends is a specific meta-model element that represents semantic relation
 * between two classes, where one class (called a “child” class) extends another
 * class (called its “parent” class) through inheritance, common to object-oriented
 * languages.
 */
class KExtends extends AbstractCodeRelationship
{
    /**
     * the child Class
     *
     * @var DataType
     */
    protected $from;

    /**
     * the parent Class
     *
     * @var DataType
     */
    protected $to;


    /**
     * @return  DataType
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param  DataType  $from
     *
     * @return  self
     */
    public function setFrom(KDMEntity $from)
    {
        if (is_a($from, DataType::class)) {
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
     * @param  DataType  $to
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
