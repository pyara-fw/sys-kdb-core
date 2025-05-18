<?php

namespace SysKDB\kdb\repository;

trait Queryable
{
    public function findByKeyValueAttribute($key, $value): DataSet
    {
        $ds = new DataSet();

        $source = $this->getDataSource();

        foreach ($source as $reg) {
            if (isset($reg[$key]) && ($reg[$key] == $value)) {
                $ds->add($reg);
            }
        }

        return $ds;
    }
}
