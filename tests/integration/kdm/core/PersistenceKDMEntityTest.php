<?php

namespace tests\integration\SysKDB\kdm\core;

use PHPUnit\Framework\TestCase;
use SysKDB\kdm\core\Element;
use SysKDB\kdm\core\KDMEntity;
use SysKDB\lib\dao\DataAccessObjectFactory;
use SysKDB\lib\dao\drivers\Redis;

class PersistenceKDMEntityTest extends TestCase
{
    public function test_crud_KDMEntity()
    {
        $entity = new KDMEntity();
        $entity->setName('my object');

        $driver = DataAccessObjectFactory::make('redis');
        $driver->setConfig([Redis::REDIS_HOST => 'redis']);
        $driver->connect();

        $result = $driver->storeObject($entity);

        $this->assertTrue(is_object($result));
        $this->assertTrue(is_a($result, Element::class));
        $this->assertTrue(!is_null($result->getOid()));

        $objectGotten = $driver->getObjectById($result->getOid());

        $this->assertTrue($result->equals($objectGotten));
        $this->assertTrue($objectGotten->equals($entity));
        $this->assertTrue($entity->equals($objectGotten));

        // Update
        $result->setName('new');

        $updatedObject = $driver->storeObject($result);
        $objectGotten = $driver->getObjectById($result->getOid());
        $this->assertTrue($result->equals($updatedObject));
        $this->assertEquals('new', $objectGotten->getName());

        // Delete
        $driver->removeObjectById($result->getOid());
        $objectGotten = $driver->getObjectById($result->getOid());
        $this->assertNull($objectGotten);
    }
}
