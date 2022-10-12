<?php

namespace SysKDB\kdb\repository;

interface QueryableInterface
{
    public function findByKeyValueAttribute($key, $value): DataSet;
}
