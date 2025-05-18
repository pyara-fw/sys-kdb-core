<?php

namespace tests\unit\SysKDB\kdb\processor;

require_once __DIR__ . '/PHPTestBase.php';

class PHPFunctionsTest extends PHPTestBase
{
    public function providerdependenciesInFunctions()
    {
        return [
            [
                'function myFunc() : string {
                    ClassName::staticMethod();
                    $object::staticMethod();
                    $object->normalMethod();
                    externalFunction();

                    $x = new ClassName();
                    $y = new ClassName2;
                }',
                [
                    'myFunc' => [
                        'return_type' => 'string',
                        'starting_line' => 1,
                        'ending_line' => 9,
                        'dependencies' => [
                            [
                                'name' => 'ClassName::staticMethod()',
                                'invoker' => 'ClassName',
                                'method' => 'staticMethod',
                                'line' => 2
                            ],
                            [
                                'name' => '$object::staticMethod()',
                                'invoker' => '$object',
                                'method' => 'staticMethod',
                                'line' => 3
                            ],
                            [
                                'name' => '$object->normalMethod()',
                                'invoker' => '$object',
                                'method' => 'normalMethod',
                                'line' => 4
                            ],
                            [
                                'name' => 'externalFunction()',
                                'function' => 'externalFunction',
                                'line' => 5
                            ],
                            [
                                'name' => 'ClassName()',
                                'class' => 'ClassName',
                                'line' => 7
                            ],
                            [
                                'name' => 'ClassName2()',
                                'class' => 'ClassName2',
                                'line' => 8
                            ],
                        ]
                    ],
                ]
            ],
            [
                '
                function myFunc() : string {
                        ClassName::staticMethod()->method2()
                            ->method3();
                }',
                [
                            'myFunc' => [
                                'return_type' => 'string',
                                'starting_line' => 2,
                                'ending_line' => 5,
                                'dependencies' => [
                                    [
                                        'name' => 'ClassName::staticMethod()',
                                        'invoker' => 'ClassName',
                                        'method' => 'staticMethod',
                                        'line' => 3
                                    ],
                                    [
                                        'name' => 'ClassName::staticMethod()->method2()',
                                        'invoker' => 'ClassName::staticMethod()',
                                        'method' => 'method2',
                                        'line' => 3
                                    ],
                                    [
                                        'name' => 'ClassName::staticMethod()->method2()->method3()',
                                        'invoker' => 'ClassName::staticMethod()->method2()',
                                        'method' => 'method3',
                                        'line' => 4
                                    ],
                                ]
                            ]

                ]
            ],
            [
                '
                function myFunc() : string {
                        $object()
                            ->method1()
                            ->method2()
                            ->method3();
                }',
                [
                            'myFunc' => [
                                'return_type' => 'string',
                                'starting_line' => 2,
                                'ending_line' => 7,
                                'dependencies' => [
                                    [
                                        'name' => '$object()',
                                        'function' => '$object',
                                        'line' => 3
                                    ],
                                    [
                                        'name' => '$object()->method1()',
                                        'invoker' => '$object()',
                                        'method' => 'method1',
                                        'line' => 4
                                    ],
                                    [
                                        'name' => '$object()->method1()->method2()',
                                        'invoker' => '$object()->method1()',
                                        'method' => 'method2',
                                        'line' => 5
                                    ],
                                    [
                                        'name' => '$object()->method1()->method2()->method3()',
                                        'invoker' => '$object()->method1()->method2()',
                                        'method' => 'method3',
                                        'line' => 6
                                    ],
                                ]
                            ]

                ]
            ],

        ];
    }

    /**
     *
     * @dataProvider providerdependenciesInFunctions
     * @param string $statement
     * @param array $expectedDetails
     * @return void
     */
    public function test_dependencies_in_functions(string $statement, array $expectedDetails)
    {
        $this->parseAndProcess($statement);


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
}
