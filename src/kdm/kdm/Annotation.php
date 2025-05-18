<?php

namespace SysKDB\kdm\kdm;

use SysKDB\kdm\core\Element;

/**
 * Annotations allow textual descriptions to be attached to any instance
 * of a model element. The meta-model Annotation class is a subclass of Element.
 *
 * @author Eduardo Luz <eduardo@eduardo-luz.com>
 * @package Pyara
 */
class Annotation extends Element
{
    /**
     * Contains the text of the annotation to the target model element.
     * @var string
     */
    protected string $text;

    /**
     *
     *
     * @var Element
     */
    protected Element $owner;

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
    public function __construct(Element $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get the value of text
     *
     * @return  string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the value of text
     *
     * @param  string  $text
     *
     * @return  self
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
