<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;

/**
 * The HasValue is a specific meta-model element that represents semantic relation
 * between a data element and its initialization element, which can be a data element
 * or an action element for complex initializations that involve expressions. HasValue
 * is an optional element that compliments the real initialization semantics by a
 * sequence of action elements in the initialization code.
 */
class HasValue extends AbstractCodeRelationship
{
    /**
     * the source data element
     *
     * @var CodeItem
     */
    protected $from;

    /**
     * the target AbstractCodeElement (datatype or action element)
     *
     * @var AbstractCodeElement
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
     * @return  AbstractCodeElement
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  AbstractCodeElement  $to  actual parameter to template instantiation
     *
     * @return  self
     */
    public function setTo(KDMEntity $to)
    {
        if (is_a($to, AbstractCodeElement::class)) {
            $this->to = $to;
        }

        return $this;
    }
}
