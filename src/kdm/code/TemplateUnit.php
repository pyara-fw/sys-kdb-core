<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\CodeItemList;

/**
 * The TemplateUnit is a meta-model element that represents parameterized datatypes,
 * common to some programming languages; for example, Ada generics, Java generics,
 * C++ templates.
 */
class TemplateUnit extends DataType
{
    /**
     * template formal parameters and the base datatype or computational object
     *
     * @var CodeItemList
     */
    protected $codeElement;

    /**
     * Get template formal parameters and the base datatype or computational object
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
}
