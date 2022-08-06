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
        $entity = new KDMEntity();

        for ($i=0;$i<10;$i++) {
            $e = new KDMEntity();
            $e->setName($i);
            $entity->getGroup()->add($e);
        }

        $iterator = $entity->getGroup();

        foreach ($iterator as $k => $item) {
            $this->assertEquals($k, $item->getName());
        }
    }
}
