<?php

namespace SysKDB\kdm\lib;

trait IndexHash
{
    protected $hashMap = [];

    /**
     * Verify if the informed key alredy exists on internal hashMap.
     * If it exists, then return true;
     * Otherwise, if the flag $updateIfDoesNotExists is true, then add
     * the key to the hashMap.
     * Return false.
     *
     *
     * @param mixed $key
     * @param boolean $updateIfDoesNotExists
     * @return boolean
     */
    protected function checkMapIfExists($key, $updateIfDoesNotExists = true): bool
    {
        if (isset($this->hashMap[$key])) {
            return true;
        }
        if ($updateIfDoesNotExists) {
            $this->hashMap[$key] = $key;
        }

        return false;
    }
}
