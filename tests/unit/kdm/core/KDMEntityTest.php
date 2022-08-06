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
}
