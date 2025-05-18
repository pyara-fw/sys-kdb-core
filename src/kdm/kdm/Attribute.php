<?php

namespace SysKDB\kdm\kdm;

use SysKDB\kdm\core\Element;

/**
 * An attribute allows information to be attached to any model element
 * in the form of a “tagged value” pair (i.e., name=value). Attribute
 * add information to the instances of model elements, as opposed to
 * stereotypes and tagged values, which apply to meta-model elements.
 *
 * @author Eduardo Luz <eduardo@eduardo-luz.com>
 * @package Pyara
 */
class Attribute extends Element
{
    /**
     * Contains the name of the attribute. This name determines the
     * semantics that are applicable to the contents of the value attribute.
     * @var string
     */
    protected string $tag;


    /**
     * Contains the current value of the attribute.
     * @var string
     */
    protected string $value;

    /**
     *
     *
     * @var Element
     */
    protected ?Element $owner;

    /**
     * Get semantics that are applicable to the contents of the value attribute.
     *
     * @return  string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Set semantics that are applicable to the contents of the value attribute.
     *
     * @param  string  $tag  semantics that are applicable to the contents of the value attribute.
     *
     * @return  self
     */
    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get contains the current value of the attribute.
     *
     * @return  string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set contains the current value of the attribute.
     *
     * @param  string  $value  Contains the current value of the attribute.
     *
     * @return  self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }


    /**
     * Get the value of owner
     *
     * @return  Element
     */
    public function getOwner(): Element
    {
        return $this->owner;
    }

    /**
     * Set the value of owner
     *
     * @param  Element  $owner
     *
     * @return  self
     */
    public function setOwner(Element $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @param Element $owner
     */
    public function __construct(?Element $owner=null)
    {
        $this->owner = $owner;
    }
}
