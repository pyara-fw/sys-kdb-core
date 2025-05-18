<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\ValueElementList;

/**
 * The ValueList class is a meta-model element that represents values of aggregated datatypes.
 */
class ValueList extends ValueElement
{
    /**
     * Component values
     *
     * @var ValueElementList
     */
    protected $valueElement;


    /**
     * Get the value of valueElement
     *
     * @return  ValueElementList
     */
    public function getValueElement()
    {
        if (!$this->valueElement) {
            $this->valueElement = new ValueElementList();
        }
        return $this->valueElement;
    }
}
