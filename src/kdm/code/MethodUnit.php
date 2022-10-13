<?php

namespace  SysKDB\kdm\code;

class MethodUnit extends ControlElement
{
    /**
     *
     *
     * @var MethodKind
     */
    protected $kind;

    /**
     * represents the visibility of the method (public, private, protected)
     *
     * @var ExportKind
     */
    protected $exportKind;

    /**
     * Get the value of kind
     *
     * @return  MethodKind
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * Set the value of kind
     *
     * @param  MethodKind  $kind
     *
     * @return  self
     */
    public function setKind(MethodKind $kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * Get represents the visibility of the method (public, private, protected)
     *
     * @return  ExportKind
     */
    public function getExportKind()
    {
        return $this->exportKind;
    }

    /**
     * Set represents the visibility of the method (public, private, protected)
     *
     * @param  ExportKind  $exportKind  represents the visibility of the method (public, private, protected)
     *
     * @return  self
     */
    public function setExportKind(ExportKind $exportKind)
    {
        $this->exportKind = $exportKind;

        return $this;
    }

    public function getReferencedAttributesMap(): array
    {
        return  parent::getReferencedAttributesMap() + [
            'kind' => 'setKind',
            'exportKind' => 'setExportKind'
        ];
    }
}
