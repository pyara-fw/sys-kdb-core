<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\AbstractCodeElementList;

/**
 * The Module class is a generic KDM modeling element that represents an entire
 * software module or a component, as determined by the programming language and
 * the software development environment. A module is a discrete and identifiable
 * program unit that contains other program elements and may be used as a logical
 * component of the software system. Usually modules promote encapsulation (i.e.,
 * information hiding) through a separation between the interface and the
 * implementation. In the context of representing existing software systems,
 * modules provide the context for establishing the associations between the
 * programming language elements that are owned by them, especially when the same
 * logical component of a software product line is compiled multiple times with
 * different compilation options and linked into multiple executables.
 */
class Module extends CodeItem
{
    /**
     *
     *
     * @var AbstractCodeElementList
     */
    protected $codeElement;

    /**
     * Get the value of codeElement
     *
     * @return  AbstractCodeElementList
     */
    public function getCodeElement()
    {
        if (!$this->codeElement) {
            $this->codeElement = new AbstractCodeElementList();
        }
        return $this->codeElement;
    }
}
