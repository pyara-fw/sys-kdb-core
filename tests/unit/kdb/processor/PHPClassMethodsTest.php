<?php

namespace tests\unit\SysKDB\kdb\processor;

require_once __DIR__.'/PHPTestBase.php';

class PHPClassMethodsTest extends PHPTestBase
{
    public function providerClassMethods()
    {
        return [
            [
                'class MyClass {

                    protected function myFunc() : string {
                        ClassName::staticMethod();
                        $object::staticMethod();
                        $object->normalMethod();
                        externalFunction();

                        $x = new ClassName();
                        $y = new ClassName2;                            
                    }
                }',
                [
                    'MyClass' => [
                        'methods' => [
                            'myFunc' => [
                                'scope' => 'protected',
                                'return_type' => 'string',
                                'starting_line' => 3,
                                'ending_line' => 11,
                                'dependencies' => [
                                    [
                                        'name' => 'ClassName::staticMethod()',
                                        'invoker' => 'ClassName',
                                        'method' => 'staticMethod',
                                        'line' => 4
                                    ],
                                    [
                                        'name' => '$object::staticMethod()',
                                        'invoker' => '$object',
                                        'method' => 'staticMethod',
                                        'line' => 5
                                    ],
                                    [
                                        'name' => '$object->normalMethod()',
                                        'invoker' => '$object',
                                        'method' => 'normalMethod',
                                        'line' => 6
                                    ],
                                    [
                                        'name' => 'externalFunction()',
                                        'function' => 'externalFunction',
                                        'line' => 7
                                    ],
                                    [
                                        'name' => 'ClassName()',
                                        'class' => 'ClassName',
                                        'line' => 9
                                    ],
                                    [
                                        'name' => 'ClassName2()',
                                        'class' => 'ClassName2',
                                        'line' => 10
                                    ],
                                ]
                            ],
                        ]
                    ]
                ]
            ],
            [
                'class MyClass2 {

                    protected function myFunc() : string {
                        ClassName::staticMethod()->method2()
                            ->method3();
                    }
                }',
                [
                    'MyClass2' => [
                        'methods' => [
                            'myFunc' => [
                                'scope' => 'protected',
                                'return_type' => 'string',
                                'starting_line' => 3,
                                'ending_line' => 6,
                                'dependencies' => [
                                    [
                                        'name' => 'ClassName::staticMethod()',
                                        'invoker' => 'ClassName',
                                        'method' => 'staticMethod',
                                        'line' => 4
                                    ],
                                    [
                                        'name' => 'ClassName::staticMethod()->method2()',
                                        'invoker' => 'ClassName::staticMethod()',
                                        'method' => 'method2',
                                        'line' => 4
                                    ],
                                    [
                                        'name' => 'ClassName::staticMethod()->method2()->method3()',
                                        'invoker' => 'ClassName::staticMethod()->method2()',
                                        'method' => 'method3',
                                        'line' => 5
                                    ],
                                ]
                            ]
                        ]
                    ]

                ]
            ],
            [
                'class MyClass3 {

                    protected function myFunc() : string {
                        $object()
                            ->method1()
                            ->method2()
                            ->method3();
                    }
                }',
                [
                    'MyClass3' => [
                        'methods' => [
                            'myFunc' => [
                                'scope' => 'protected',
                                'return_type' => 'string',
                                'starting_line' => 3,
                                'ending_line' => 8,
                                'dependencies' => [
                                    [
                                        'name' => '$object()',
                                        'function' => '$object',
                                        'line' => 4
                                    ],
                                    [
                                        'name' => '$object()->method1()',
                                        'invoker' => '$object()',
                                        'method' => 'method1',
                                        'line' => 5
                                    ],
                                    [
                                        'name' => '$object()->method1()->method2()',
                                        'invoker' => '$object()->method1()',
                                        'method' => 'method2',
                                        'line' => 6
                                    ],
                                    [
                                        'name' => '$object()->method1()->method2()->method3()',
                                        'invoker' => '$object()->method1()->method2()',
                                        'method' => 'method3',
                                        'line' => 7
                                    ],
                                ]
                            ]
                        ]
                    ]

                ]
            ],

        ];
    }

    /**
     *
     * @dataProvider providerClassMethods
     * @param string $statement
     * @param array $expectedDetails
     * @return void
     */
    public function test_class_methods(string $statement, array $expectedDetails)
    {
        $this->parseAndProcess($statement);


        $declaredClasses = $this->processor->getArrayDeclaredClassNames();

        foreach ($declaredClasses as $className) {
            $details = $this->processor->getAssocClass($className);
            //hashGet('declared_classes', $className);
            $this->assertTrue(isset($expectedDetails[$className]));
            foreach ($expectedDetails[$className] as $key => $expectedValue) {
                $this->assertArrayHasKey($key, $details);
                $this->assertEquals($expectedValue, $details[$key]);
            }
        }
    }
}
