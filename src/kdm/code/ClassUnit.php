<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\CodeItemList;

/**
 * The ClassUnit is a meta-model element that represents user-defined classes
 * in object-oriented languages. A class datatype is a named datatype that
 * represents a class: an ordered collection of named elements, each of which
 * can be another CodeItem, such as a MemberUnit or a MethodUnit.
 */
class ClassUnit extends DataType
{
    /**
     * The indicator of an abstract class
     *
     * @var bool
     */
    protected $isAbstract = false;

    /**
     *
     *
     * @var CodeItemList
     */
    protected $codeElement;

    /**
     * Get the indicator of an abstract class
     *
     * @return  bool
     */
    public function getIsAbstract()
    {
        return $this->isAbstract;
    }

    /**
     * Set the indicator of an abstract class
     *
     * @param  bool  $isAbstract  The indicator of an abstract class
     *
     * @return  self
     */
    public function setIsAbstract(bool $isAbstract)
    {
        $this->isAbstract = $isAbstract;

        return $this;
    }

    /**
     * Get the value of codeElement
     *
     * @return  CodeItemList
     */
    public function getCodeElement()
    {
        if (!$this->codeElement) {
            $this->codeElement = new CodeItemList();
        }
        return $this->codeElement;
    }
}
