<?php

namespace SysKDB\kdb\repository;

use SysKDB\kdm\core\Element;

trait Queryable
{
    public function findByKeyValueAttribute($key, $value): DataSet
    {
        $ds = new DataSet();

        $source = $this->getDataSource();

        foreach ($source as $record) {
            if (is_object($record) && is_a($record, Element::class)) {
                $reg = $record->exportVars();
            } else {
                $reg = $record;
            }

            if (is_array($reg)) {
                if (isset($reg[$key]) && ($reg[$key] == $value)) {
                    $ds->add($reg);
                }
                // } elseif (is_object($reg) && is_a($reg, Element::class)) {
            //     $record = $reg->exportVars();
            //     if ()
            //     $ds->add();
            } else {
                throw new \Exception("Invalid 'reg' format");
            }
        }

        return $ds;
    }
}
