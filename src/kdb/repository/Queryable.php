<?php

namespace SysKDB\kdb\repository;

use SysKDB\kdm\core\Element;

trait Queryable
{
    public function findByKeyValueAttribute($key, $value): DataSet
    {
        $ds = new DataSet();

        $source = $this->getDataSource();

        foreach ($source as $reg) {
            if (is_array($reg)) {
                if (isset($reg[$key]) && ($reg[$key] == $value)) {
                    $ds->add($reg);
                }
            } elseif (is_object($reg) && is_a($reg, Element::class)) {
                $ds->add($reg->exportVars());
            } else {
                throw new \Exception("Invalid 'reg' format");
            }
        }

        return $ds;
    }
}
