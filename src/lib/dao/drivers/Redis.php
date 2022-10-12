<?php

namespace SysKDB\lib\dao\drivers;

use SysKDB\kdm\lib\HasConfig;
use SysKDB\lib\dao\DataAccessObject;
use SysKDB\lib\exception\ConnectionException;
use SysKDB\lib\dao\PersistentObject;
use SysKDB\lib\dao\PersistentObjectFactory;
use SysKDB\lib\exception\ConnectionNotEstablishedException;
use SysKDB\lib\exception\InvalidArgumentException;
use SysKDB\lib\UniqueId;

/**
 *
 */
class Redis extends DataAccessObject
{
    use HasConfig;
    public const REDIS_HOST = 'redis_host';

    /**
     * @var \Redis
     */
    protected $connection;

    public function __construct(?array $config=[])
    {
        $this->setConfig($config);
    }

    protected function requiredConnection()
    {
        if (!$this->connection) {
            throw new ConnectionNotEstablishedException("There is no active connection");
        }
    }

    /**
     *
     * @return mixed
     */
    public function connect()
    {
        if (!$this->hasConfigItem(static::REDIS_HOST)) {
            throw new ConnectionException("Configuration key " .static::REDIS_HOST . " is not present. Aborting.");
        }
        $this->connection = new \Redis();
        $this->connection->connect($this->getConfigItem(static::REDIS_HOST));
    }

    /**
     *
     * @return mixed
     */
    public function disconnect()
    {
        $this->connection->disconnect();
    }

    /**
     *
     * @param string $oid
     *
     * @return \SysKDB\lib\dao\PersistentObject
     */
    public function getObjectById(string $oid): ?PersistentObject
    {
        $this->requiredConnection();

        $serializedObject = $this->connection->get($oid);
        if (!$serializedObject) {
            return null;
        }
        // $object = $serializedObject
        $object = PersistentObjectFactory::makeFromSerialized($serializedObject);
        return $object;
    }

    /**
     *
     * @param PersistentObject $object
     *
     * @return ?PersistentObject
     */
    public function storeObject(PersistentObject $object): PersistentObject
    {
        $this->requiredConnection();

        if (!$object->getOid()) {
            $oid = UniqueId::get();
            $object->setOid($oid);
        }

        $serializedObject = $object->serialize();
        $this->connection->set($object->getOid(), $serializedObject);

        return $object;
    }

    /**
     *
     * @param string $oid
     *
     * @return bool
     */
    public function removeObjectById(string $oid): bool
    {
        $this->requiredConnection();
        return $this->connection->del($oid);
    }
}
