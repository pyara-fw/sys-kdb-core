<?php

namespace  SysKDB\kdm\code;

/**
 *
 */
class RangeType extends DerivedType
{
    /**
     * @var int
     */
    protected $lower;

    /**
     * @var int
     */
    protected $upper;

    /**
     * Get the value of lower
     *
     * @return  int
     */
    public function getLower()
    {
        return $this->lower;
    }

    /**
     * Set the value of lower
     *
     * @param  int  $lower
     *
     * @return  self
     */
    public function setLower(int $lower)
    {
        $this->lower = $lower;

        return $this;
    }

    /**
     * Get the value of upper
     *
     * @return  int
     */
    public function getUpper()
    {
        return $this->upper;
    }

    /**
     * Set the value of upper
     *
     * @param  int  $upper
     *
     * @return  self
     */
    public function setUpper(int $upper)
    {
        $this->upper = $upper;

        return $this;
    }
}
