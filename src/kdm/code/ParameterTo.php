<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;

/**
 * The ParameterTo is a meta-model element that represents an actual
 * type parameter in the context of a reference to a parameterized entity.
 * ParameterTo is “parametrization” relation between an AbstractCodeElement
 * (for example, a TemplateType or an ActionElement) and a CodeItem.
 */
class ParameterTo extends AbstractCodeRelationship
{
    /**
     * the reference to the parameterized entity (the context of the actual type parameter).
     *
     * @var AbstractCodeElement
     */
    protected $from;

    /**
     * actual parameter to template instantiation
     *
     * @var CodeItem
     */
    protected $to;


    /**
     * Get the reference to the parameterized entity (the context of the actual type parameter).
     *
     * @return  AbstractCodeElement
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the reference to the parameterized entity (the context of the actual type parameter)
     *
     * @param  AbstractCodeElement  $from
     *
     * @return  self
     */
    public function setFrom(KDMEntity $from)
    {
        if (is_a($from, AbstractCodeElement::class)) {
            $this->from = $from;
        }

        return $this;
    }

    /**
     * Get actual parameter to template instantiation
     *
     * @return  CodeItem
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set actual parameter to template instantiation
     *
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
