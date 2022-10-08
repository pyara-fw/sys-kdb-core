<?php

namespace  SysKDB\kdm\code;

/**
 * The CallableUnit represents a basic stand-alone element that can be
 * called, such as a procedure or a function.
 */
class CallableUnit extends ControlElement
{
    /**
     * @var CallableKind
     */
    protected $kind;

    /**
     * Get the value of kind
     *
     * @return  CallableKind
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set the value of kind
     *
     * @param  CallableKind  $kind
     *
     * @return  self
     */
    public function setKind(CallableKind $kind)
    {
        $this->kind = $kind;

        return $this;
    }
}
