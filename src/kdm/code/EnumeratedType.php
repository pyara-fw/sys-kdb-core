<?php

namespace  SysKDB\kdm\code;

/**
 * Values of the Enumerated and State datatypes are represented by a Value
 * meta-model element that is owned by the EnumeratedType.
 */
class EnumeratedType extends DataType
{
    /**
     * The list of enumerated literals defined for the given EnumeratedType.
     *
     * @var Value
     */
    protected $value;

    /**
     * Get the list of enumerated literals defined for the given EnumeratedType.
     *
     * @return  Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the list of enumerated literals defined for the given EnumeratedType.
     *
     * @param  Value  $value  The list of enumerated literals defined for the given EnumeratedType.
     *
     * @return  self
     */
    public function setValue(Value $value)
    {
        $this->value = $value;

        return $this;
    }
}
