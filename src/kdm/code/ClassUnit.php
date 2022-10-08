<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\omg\mof\DataType;

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
     * @var boolean
     */
    protected $isAbstract = false;
}
