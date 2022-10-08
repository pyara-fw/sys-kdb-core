<?php

namespace SysKDB\kdm\kdm;

use SysKDB\kdm\core\ModelElement;

/**
 * The KDMFramework meta-model element is an abstract class that describes
 * the common properties of all KDM Framework elements. KDMFramework class
 * is extended by Segment and KDMModel classes. These elements are contains
 * for KDM light-weight extensions (extension property).
 */
abstract class KDMFramework extends ModelElement
{
    /**
     * The name of the framework element.
     *
     * @var string
     */
    protected $name;

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     *
     * @return  self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}
