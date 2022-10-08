<?php

namespace SysKDB\kdm\lib;

/**
 *
 */
trait HasPath
{
    /**
     *
     *
     * @var string
     */
    protected $path;

    /**
     * Get the value of path
     *
     * @return  string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set the value of path
     *
     * @param  string  $path
     *
     * @return  self
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }
}
