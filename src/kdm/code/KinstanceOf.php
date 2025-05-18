<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;

/**
 * Represents the InstanceOf from KDM. Due the fact of PHP has 'InstanceOf' as
 * reserved word, it was necessary to rename it.
 *
 * The InstanceOf is a meta-model element that represents “instantiation” relation
 * between an AbstractCodeElement (for example, a ClassUnit) and a TemplateUnit.
 * In the meta-model InstanceOf is a subclass of AbstractCodeRelationship.
 */
class KinstanceOf extends AbstractCodeRelationship
{
    /**
     * The AbstractCodeElement that represents the instantiation of a template.
     *
     * @var AbstractCodeElement
     */
    protected $from;

    /**
     * The TemplateUnit that is being instantiated.
     *
     * @var TemplateUnit
     */
    protected $to;

    /**
     * Get the AbstractCodeElement that represents the instantiation of a template.
     *
     * @return  AbstractCodeElement
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the AbstractCodeElement that represents the instantiation of a template.
     *
     * @param  AbstractCodeElement  $from  The AbstractCodeElement that represents the instantiation of a template.
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
     * Get the TemplateUnit that is being instantiated.
     *
     * @return  TemplateUnit
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the TemplateUnit that is being instantiated.
     *
     * @param  TemplateUnit  $to  The TemplateUnit that is being instantiated.
     *
     * @return  self
     */
    public function setTo(KDMEntity $to)
    {
        if (is_a($to, TemplateUnit::class)) {
            $this->to = $to;
        }

        return $this;
    }
}
