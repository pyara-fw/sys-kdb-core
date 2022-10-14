<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;

/**
 * The Extends is a specific meta-model element that represents semantic relation
 * between two classes, where one class (called a “child” class) extends another
 * class (called its “parent” class) through inheritance, common to object-oriented
 * languages.
 */
class KExtends extends AbstractCodeRelationship
{
    /**
     * @return  DataType
     */
    public function getChild()
    {
        return $this->from;
    }


    /**
     * @param  DataType  $from
     *
     * @return  self
     */
    public function setChild(KDMEntity $from)
    {
        if (is_a($from, DataType::class)) {
            $this->from = $from;
            // if (is_callable($from->getCodeRelation())) {
            $from->getCodeRelation()->add($this);
            // }
        }

        return $this;
    }

    /**
     * @return  DataType
     */
    public function getParent()
    {
        return $this->to;
    }

    /**
     * @param  DataType  $to
     *
     * @return  self
     */
    public function setParent(KDMEntity $to)
    {
        if (is_a($to, DataType::class)) {
            $this->to = $to;
            // if (is_callable($to->getCodeRelation())) {
            $to->getCodeRelation()->add($this);
            // }
        }

        return $this;
    }

    public function getReferencedAttributesMap(): array
    {
        return  parent::getReferencedAttributesMap() + [
            'from' => 'setChild', 'to' => 'setParent'
        ];
    }
}
