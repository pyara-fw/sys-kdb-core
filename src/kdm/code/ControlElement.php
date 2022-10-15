<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\AbstractCodeElementList;

/**
 * The ControlElement class is a common superclass that defines attributes
 * for callable code elements. In the meta-model it has the role of an
 * endpoint for some KDM relations.
 */
class ControlElement extends ComputationalObject
{
    /**
     * Optional association to the datatype of this control element
     *
     * @var DataType
     */
    protected $dataType;

    /**
     * Represents owned code elements, such as local definitions and actions.
     *
     * @var AbstractCodeElementList
     */
    protected $codeElement;

    /**
     * Get the value of dataType
     *
     * @return  DataType
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * Set the value of dataType
     *
     * @param  DataType  $dataType
     *
     * @return  self
     */
    public function setDataType(DataType $dataType)
    {
        $this->dataType = $dataType;

        return $this;
    }

    /**
     * Get represents owned code elements, such as local definitions and actions.
     *
     * @return  AbstractCodeElementList
     */
    public function getCodeElement()
    {
        if (!is_object($this->codeElement)) {
            $this->codeElement = new AbstractCodeElementList();
        }
        return $this->codeElement;
    }


    public function setCodeElement($codeElement)
    {
        $this->codeElement = $codeElement;
    }


    public function getReferencedAttributesMap(): array
    {
        return  parent::getReferencedAttributesMap() + [
            'dataType' => 'setDataType',
            'codeElement' => 'setCodeElement'
        ];
    }
}
