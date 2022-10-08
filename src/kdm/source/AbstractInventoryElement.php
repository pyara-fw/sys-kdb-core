<?php

namespace SysKDB\kdm\source;

use SysKDB\kdm\core\KDMEntity;
use SysKDB\kdm\lib\AbstractInventoryRelationshipList;

/**
 * The AbstractInventoryElement is the abstract parent class for
 * all inventory entities.
 */
abstract class AbstractInventoryElement extends KDMEntity
{
    /**
     *
     *
     * @var InventoryModel
     */
    protected $model;



    /**
     * List of inventory's elements of which this element depends on.
     *
     * @var AbstractInventoryRelationshipList
     */
    protected $dependencies;

    /**
     * List of inventory's elements that depends of this particular element.
     *
     * @var AbstractInventoryRelationshipList
     */
    protected $dependents;

    /**
     * Get the value of model
     *
     * @return  InventoryModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the value of model
     *
     * @param  InventoryModel  $model
     *
     * @return  self
     */
    public function setModel(InventoryModel $model)
    {
        $this->model = $model;

        return $this;
    }



    /**
     * Get list of inventory's elements of which this element depends on.
     *
     * @return  AbstractInventoryRelationshipList
     */
    public function getDependencies()
    {
        if (!$this->dependencies) {
            $this->dependencies = new AbstractInventoryRelationshipList();
        }
        return $this->dependencies;
    }

    /**
     * Set list of inventory's elements of which this element depends on.
     *
     * @param  AbstractInventoryRelationshipList  $dependencies  List of inventory's elements of which this element depends on.
     *
     * @return  self
     */
    public function setDependencies(AbstractInventoryRelationshipList $dependencies)
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Get list of inventory's elements that depends of this particular element.
     *
     * @return  AbstractInventoryRelationshipList
     */
    public function getDependents()
    {
        if (!$this->dependents) {
            $this->dependents = new AbstractInventoryRelationshipList();
        }
        return $this->dependents;
    }

    /**
     * Set list of inventory's elements that depends of this particular element.
     *
     * @param  AbstractInventoryRelationshipList  $dependents  List of inventory's elements that depends of this particular element.
     *
     * @return  self
     */
    public function setDependents(AbstractInventoryRelationshipList $dependents)
    {
        $this->dependents = $dependents;

        return $this;
    }
}
