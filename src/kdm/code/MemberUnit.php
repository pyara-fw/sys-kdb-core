<?php

namespace  SysKDB\kdm\code;

/**
 * MemberUnit class is a concrete subclass of the DataElement class that
 * represents a member of a class type.
 *
 * Constraints:
 * - MemberUnit can be owned only by a ClassUnit.
 */
class MemberUnit extends DataElement
{
    /**
     * Represents the visibility of the member (public, private, protected).
     *
     * @var ExportKind
     */
    protected $export;

    /**
     * Get represents the visibility of the member (public, private, protected).
     *
     * @return  ExportKind
     */
    public function getExport()
    {
        return $this->export;
    }

    /**
     * Set represents the visibility of the member (public, private, protected).
     *
     * @param  ExportKind  $export  Represents the visibility of the member (public, private, protected).
     *
     * @return  self
     */
    public function setExport(ExportKind $export)
    {
        $this->export = $export;

        return $this;
    }
}
