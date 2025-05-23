<?php

namespace tests\unit\SysKDB\kdm\core;

use PHPUnit\Framework\TestCase;
use SysKDB\kdm\core\KDMEntity;

/**
 * KDMEntityTest
 * @group group
 */
class KDMEntityTest extends TestCase
{
    /** @test */
    public function test_get_and_set_name()
    {
        $expectedName = 'entity_name';

        $entity = new KDMEntity();
        $entity->setName($expectedName);

        $actualName = $entity->getName();

        $this->assertEquals($expectedName, $actualName);
    }

    public function test_entity_with_group()
    {
        $entityNameTemplate = 'Entity%d';
        $entity = new KDMEntity();

        for ($i=0;$i<10;$i++) {
            $e = new KDMEntity();
            $e->setName(sprintf($entityNameTemplate, $i));
            ($entity->getGroup())->add($e);
        }

        $iterator = $entity->getGroup();

        foreach ($iterator as $k => $item) {
            $this->assertEquals(sprintf($entityNameTemplate, $k), $item->getName());
        }
    }


    public function test_entity_with_owner()
    {
        define('OWNER_NAME', 'Owner');

        $owner = new KDMEntity();
        $owner->setName(OWNER_NAME);

        $entity = new KDMEntity();

        // Before assign an owner, the entity's owner is null
        $this->assertNull($entity->getOwner());

        // Before set the entity's owner, the owner's ownedElements should be empty
        $this->assertCount(0, $owner->getOwnedElements());

        $entity->setOwner($owner);

        // After assign the owner, the owner's ownedElements should have only one element
        $this->assertEquals(OWNER_NAME, $entity->getOwner()->getName());
        $this->assertCount(1, $owner->getOwnedElements());
    }
}
