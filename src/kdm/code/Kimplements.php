<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;

/**
 * The Implements is a meta-model element that represents “implementation”
 * association between a CodeItem (for example, a ClassUnit) and an InterfaceUnit.
 * “Implements” relationship is similar to “Extends.” For example, Java “implements”
 * construct can be represented by KDM “Implements” relationship.
 */
class Kimplements extends AbstractCodeRelationship
{
    /**
     * The CodeItem that implements a certain InterfaceUnit.
     *
     * @var CodeItem
     */
    protected $from;

    /**
     * The InterfaceUnit that is being implemented by CodeItem.
     *
     * @var CodeItem
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
     * @return  CodeItem
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  CodeItem  $to  actual parameter to template instantiation
     *
     * @return  self
     */
    public function setTo(KDMEntity $to)
    {
        if (is_a($to, CodeItem::class)) {
            $this->to = $to;
        }

        return $this;
    }
}
