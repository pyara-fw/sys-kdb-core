<?php

namespace tests\unit\SysKDB\kdb\processor;

require_once __DIR__.'/PHPTestBase.php';


class PHPPlainTest extends PHPTestBase
{
    public function providerGetFunctions()
    {
        return [
            [
                'class MyClass {
                    public function x() {}
                }
                
                function add($x,$y) {}

                function sub($x,$y) {}
                ',
                [
                    'add' => ['starting_line'=>5, 'ending_line'=>5],
                    'sub' => []
                ]
            ],
            [
                'function mul(int $x, int $y): int {} ',
                [
                    'mul' => [
                        'return_type' => 'int',
                        'starting_line' => 1,
                        'ending_line' => 1
                    ]
                ]
            ]
        ];
    }



    /**
     *
     * @dataProvider providerGetFunctions
     * @param [type] $snippet
     * @param [type] $expected
     * @return void
     */
    public function test_get_functions($snippet, $expectedDetails)
    {
        $this->parseAndProcess($snippet);


        $declaredFunctions = $this->processor->getArrayDeclaredFunctionNames();

        foreach ($declaredFunctions as $functionName) {
            $details = $this->processor->getAssocFunction($functionName);
            $this->assertTrue(isset($expectedDetails[$functionName]));
            foreach ($expectedDetails[$functionName] as $key => $expectedValue) {
                $this->assertArrayHasKey($key, $details);
                $this->assertEquals($expectedValue, $details[$key]);
            }
        }
    }



    public function providerGetIncludes()
    {
        return [
            [
                '
                    include_once "/var/www/lib/file1.php";
                    include __DIR__ . "/../file2.php";
                ',
                [
                    [
                        'type' => 'include_once',
                        'path' => '"/var/www/lib/file1.php"'
                    ],
                    [
                        'type' => 'include',
                        'path' => '__DIR__ . "/../file2.php"'
                    ]
                ]
            ],
        ];
    }


    /**
     *
     * @dataProvider providerGetIncludes
     * @param [type] $snippet
     * @param [type] $expectedDetails
     * @return void
     */
    public function test_get_includes($snippet, $expectedDetails)
    {
        $this->parseAndProcess($snippet);
        $includes = $this->processor->getArray('includes', []);
        $this->assertCount(count($expectedDetails), $includes);
        foreach ($expectedDetails as $k => $itemInclude) {
            $found = false;
            foreach ($includes as $k2 => $itemInclude2) {
                if ($itemInclude['path'] == $itemInclude2['path']) {
                    $found = true;
                    break;
                }
            }
            $this->assertTrue($found, "Not found item $k (" . json_encode($itemInclude).")");
        }
    }
}
