<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\core\KDMEntity;

/**
 * Includes class represents the relationship from an IncludeDirective to a
 * SharedUnit that represents the code elements being included.
 */
class Includes extends AbstractCodeRelationship
{
    /**
     * the code elements being included (usually a SharedUnit)
     *
     * @var PreprocessorDirective
     */
    protected $from;

    /**
     * The IncludeDirective class that represents the include directive.
     *
     * @var AbstractCodeElement
     */
    protected $to;

    /**
     * @return  PreprocessorDirective
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param  PreprocessorDirective  $from
     *
     * @return  self
     */
    public function setFrom(KDMEntity $from)
    {
        if (is_a($from, PreprocessorDirective::class)) {
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
     *
     * @param  AbstractCodeElement  $to
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
