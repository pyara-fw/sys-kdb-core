<?php

namespace tests\functional\SysKDB\kdm;

use PHPUnit\Framework\TestCase;
use SysKDB\kdb\processor\PHP;

/**
 * ExtractMetamodelFromCodeTest
 * @group group
 */
class ExtractMetamodelFromCodeTest extends TestCase
{
    public function test_code01()
    {
        $processor = new PHP();

        $contents = file_get_contents(__DIR__.'/code/code01.php');

        $processor->parseAndProcess($contents);

        $classes = $processor->getClasses();

        $this->assertTrue(is_array($classes));
        $this->assertCount(1, $classes);

        // print_r($classes);

        $this->assertArrayHasKey('code\lib\Car', $classes);

        $carClass = $classes['code\lib\Car'];

        $this->assertArrayHasKey('namespace', $carClass);
        $this->assertEquals('code\lib', $carClass['namespace']);

        $this->assertEquals('class', $carClass['type']);
        $this->assertEquals('Car', $carClass['name']);
        $this->assertEmpty($carClass['extends']);

        $this->assertCount(6, $carClass['attributes']);
        $this->assertCount(6, $carClass['methods']);

        $attributes = [
            '$myPrivate'=>[
                'scope' => 'private',
                'value' => "'1'"
            ],
            '$myProtected' => [
                'scope' => 'protected',
                'value' => 2
            ],
            '$myVar' => [
                'scope' => 'public',
            ],
            '$myPublic' => [
                'scope' => 'public',
            ]
        ];

        foreach ($attributes as $name => $metaData) {
            $this->assertArrayHasKey($name, $carClass['attributes']);
            foreach ($metaData as $metaFieldName => $metaFieldValue) {
                $this->assertArrayHasKey($metaFieldName, $carClass['attributes'][$name]);
                $this->assertEquals($metaFieldValue, $carClass['attributes'][$name][$metaFieldName]);
            }
        }

        $methods = [
            'init' => [
                'scope' => 'public'
            ],
            'getVin' => [
                'scope' => 'public',
                'return_type' => 'string'
            ],
            'addPart' => [
                'scope' => 'protected',
            ],
        ];


        foreach ($methods as $name => $metaData) {
            $this->assertArrayHasKey($name, $carClass['methods']);
            foreach ($metaData as $metaFieldName => $metaFieldValue) {
                $this->assertArrayHasKey($metaFieldName, $carClass['methods'][$name]);
                $this->assertEquals($metaFieldValue, $carClass['methods'][$name][$metaFieldName]);
            }
        }
    }
}
