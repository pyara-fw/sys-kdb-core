<?php

namespace SysKDB\lib\dao;

interface PersistentObject
{
    public function getOid();
    public function setOid(string $oid);

    public function serialize(): string;

    public function unserialize(string $str);

    public function equals($toCompare): bool;


    public function store(): PersistentObject;
    public function remove(): bool;
}
