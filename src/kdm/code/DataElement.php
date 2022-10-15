<?php

namespace  SysKDB\kdm\code;

/**
 * The DataElement class is a generic modeling element that defines the common
 * properties of several concrete classes that represent the named data items
 * of existing software systems (for example, global and local variables, record
 * files, and formal parameters). KDM models usually use specific concrete
 * subclasses. The DataElement class itself is a concrete class that can be used
 * as an extended code element, with a certain stereotype. As an extended element
 * DataElement is more specific than CodeElement.
 */
class DataElement extends ComputationalObject
{
    /**
     * Optional extension representing the original representation of the data element.
     *
     * @var string
     */
    protected $ext;


    /**
     * Specifies the optional constraint on the number of elements any value of the
     * storable element may contain according to the semantics of the base datatype.
     *
     * Size attribute corresponds to the maximum- size bound in a size-subtype of the
     * base datatype.
     *
     * @var int|null
     */
    protected $size;


    /**
     * The datatype of the DataElement that describes the values of the DataElement.
     *
     * @var DataType
     */
    protected $type;

    /**
     * Get optional extension representing the original representation of the data element.
     *
     * @return  string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set optional extension representing the original representation of the data element.
     *
     * @param  string  $ext  Optional extension representing the original representation of the data element.
     *
     * @return  self
     */
    public function setExt(string $ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get base datatype.
     *
     * @return  int|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set base datatype.
     *
     * @param  int|null  $size  base datatype.
     *
     * @return  self
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the datatype of the DataElement that describes the values of the DataElement.
     *
     * @return  DataType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the datatype of the DataElement that describes the values of the DataElement.
     *
     * @param  DataType  $type  The datatype of the DataElement that describes the values of the DataElement.
     *
     * @return  self
     */
    public function setType(DataType $type)
    {
        $this->type = $type;

        return $this;
    }


    public function getReferencedAttributesMap(): array
    {
        return  parent::getReferencedAttributesMap() + [
            'type' => 'setType',
            'size' => 'setSize',
            'ext' => 'setExt',
        ];
    }
}
