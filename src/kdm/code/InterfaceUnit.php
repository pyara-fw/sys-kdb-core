<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\CodeItemList;
use SysKDB\kdm\lib\omg\mof\DataType;

/**
 * The InterfaceUnit is a meta-model element that represents the
 * interface concept common to various programming languages.
 */
class InterfaceUnit extends DataType
{
    /**
     *
     *
     * @var CodeItemList
     */
    protected $codeElement;


    /**
     * Get the value of codeElement
     *
     * @return  CodeItemList
     */
    public function getCodeElement()
    {
        if (!is_object($this->codeElement)) {
            $this->codeElement = new CodeItemList();
        }
        return $this->codeElement;
    }

    public function addCodeElement($element)
    {
        $this->getCodeElement()->add($element);
    }

    public function getReferencedAttributesMap(): array
    {
        return  parent::getReferencedAttributesMap() + [
            'codeElement' => 'addCodeElement'
        ];
    }
}
