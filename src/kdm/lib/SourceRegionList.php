<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\source\SourceRegion;

class SourceRegionList extends ListBase
{
    /**
     *
     *
     * @param SourceRegion $ref
     * @return void
     */
    public function add(SourceRegion $ref)
    {
        array_push($this->list, $ref);
    }

    /**
     * @param integer $i
     * @return SourceRegion|null
     */
    public function get(int $i): ?SourceRegion
    {
        return  $this->list[$i] ?? null;
    }
}
