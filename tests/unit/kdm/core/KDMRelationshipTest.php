<?php

namespace tests\unit\SysKDB\kdm\core;

use PHPUnit\Framework\TestCase;
use SysKDB\kdm\core\KDMEntity;
use SysKDB\kdm\core\KDMRelationship;

/**
 * KDMRelationshipTest
 * @group group
 */
class KDMRelationshipTest extends TestCase
{
    public function test_adding_some_relationships()
    {
        $lsEntities = [];
        $countEntities = 5;

        for ($i=0; $i< $countEntities; $i++) {
            $expectedName = 'entity_' . $i;
            $entity = new KDMEntity();
            $entity->setName($expectedName);
            $lsEntities[] = $entity;
        }

        $firstEntity = reset($lsEntities);
        $relationships = [];
        foreach ($lsEntities as $k => $entity) {
            if ($k == 0) {
                continue;
            }
            $relationships[] = new KDMRelationship($firstEntity, $entity);
        }

        $this->assertCount($countEntities-1, $relationships);


        foreach ($lsEntities as $k => $entity) {
            if ($k == 0) {
                $this->assertCount($countEntities-1, $entity->getOutbound());
                $this->assertCount(0, $entity->getInbound());
            } else {
                $this->assertCount(0, $entity->getOutbound());
                $this->assertCount(1, $entity->getInbound());
            }
        }
    }
}
