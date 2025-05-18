<?php

namespace SysKDB\kdm\lib;

use SysKDB\kdm\code\CommentUnit;

class CommentUnitList extends ListBase
{
    /**
     *
     *
     * @param CommentUnit $element
     * @return void
     */
    public function add(CommentUnit $element)
    {
        array_push($this->list, $element);
    }

    /**
     * @param integer $i
     * @return CommentUnit|null
     */
    public function get(int $i): ?CommentUnit
    {
        return  $this->list[$i] ?? null;
    }
}
