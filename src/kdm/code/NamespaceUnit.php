<?php

namespace  SysKDB\kdm\code;

use SysKDB\kdm\lib\CodeItemList;

/**
 * The NamespaceUnit is a specific meta-model element that represents the target
 * of the VisibleIn or Imports visibility relationships.
 */
class NamespaceUnit extends CodeItem
{
    /**
     * A KDM group of code elements that belong to the namespace.
     * The actual owners of these elements are the corresponding modules,
     * not the namespace, since namespaces can, in general cross cut the
     * module boundaries.
     *
     * @var CodeItemList
     */
    protected $groupedCode;


    /**
     * @return  CodeItemList
     */
    public function getGroupedCode()
    {
        if (!is_object($this->groupedCode)) {
            $this->groupedCode = new CodeItemList();
        }
        return $this->groupedCode;
    }
}
