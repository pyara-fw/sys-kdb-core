<?php

namespace SysKDB\kdm\source;

/**
 * DependsOn class is a meta-model element that represents an optional relationship
 * between two inventory items, in which one inventory element requires another
 * inventory element during one or more steps of the engineering process.
 *
 * When the origin of the DependsOn relationship is an Inventory container, this
 * means that all elements owned by this container (directly or indirectly) depend
 * on the target of the relationship.
 *
 * When the target of the “DependsOn” relationship is an Inventory container, this
 * means that the one or more base inventory elements (the origin of the relationship)
 * depends on all elements owned by the container (directly or indirectly).
 */
class DependsOn extends AbstractInventoryRelationship
{
    /**
     * the base inventory item
     *
     * @var AbstractInventoryElement
     */
    protected $from;

    /**
     * another inventory item on which the base item depends
     *
     * @var AbstractInventoryElement
     */
    protected $to;

    /**
     * Get the base inventory item
     *
     * @return  AbstractInventoryElement
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the base inventory item
     *
     * @param  AbstractInventoryElement  $from  the base inventory item
     *
     * @return  self
     */
    public function setFrom(AbstractInventoryElement $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get another inventory item on which the base item depends
     *
     * @return  AbstractInventoryElement
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set another inventory item on which the base item depends
     *
     * @param  AbstractInventoryElement  $to  another inventory item on which the base item depends
     *
     * @return  self
     */
    public function setTo(AbstractInventoryElement $to)
    {
        $this->to = $to;

        return $this;
    }
}
