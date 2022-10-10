<?php

namespace  SysKDB\kdm\code;

/**
 *
 */
class ArrayType extends DerivedType
{
    /**
     * @var int
     */
    protected $size;

    /**
     * @var IndexUnit
     */
    protected $indexUnit;

    /**
     * Get the value of size
     *
     * @return  int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @param  int  $size
     *
     * @return  self
     */
    public function setSize(int $size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of indexUnit
     *
     * @return  IndexUnit
     */
    public function getIndexUnit()
    {
        return $this->indexUnit;
    }

    /**
     * Set the value of indexUnit
     *
     * @param  IndexUnit  $indexUnit
     *
     * @return  self
     */
    public function setIndexUnit(IndexUnit $indexUnit)
    {
        $this->indexUnit = $indexUnit;

        return $this;
    }
}
