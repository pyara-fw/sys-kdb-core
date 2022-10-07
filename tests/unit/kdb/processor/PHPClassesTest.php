<?php

namespace tests\unit\SysKDB\kdb\processor;

require_once __DIR__.'/PHPTestBase.php';

class PHPClassesTest extends PHPTestBase
{
    public function providerGetSimpleClassName()
    {
        return [
            ['class MyClass {} ', 'MyClass',1],
            ['abstract class MyClass2 {} ', 'MyClass2',1],
            ['abstract iterface MyInterface {} ', null,0],
        ];
    }

    /**
     *
     * @dataProvider providerGetSimpleClassName
     * @param string $statement
     * @return void
     */
    public function test_get_simple_class_name($statement, $expectedClassName, $countExpectedClasses)
    {
        $this->parseAndProcess($statement);

        $this->assertEquals($expectedClassName, $this->processor->getVar('current_class_name'));
        $this->assertCount($countExpectedClasses, $this->processor->getArrayDeclaredClassNames());
    }



    public function providerGetClassDetails()
    {
        return [
            ['
            namespace X\Y;
            class MyClass0 {}', ['MyClass0'=>['name' => 'MyClass0', 'namespace'=>'X\\Y']],
        ],
            ['abstract class MyClass1 extends MyParent {}',
                ['MyClass1'=>
                    [
                        'name' => 'MyClass1',
                        'extends' => 'MyParent',
                        'type' => 'class',
                        'is_abstract' => true,
                        'starting_line' => 1,
                    ]
                ],
            ],
            ["// line 1
// line 2
// line 3
              class MyClass2 implements A { // line 4
                // line 5
              } // line 6",
                ['MyClass2'=>
                    [
                        'name' => 'MyClass2',
                        'implements' => ['A'],
                        'starting_line' => 4,
                        'ending_line' => 6,
                    ]
                ],
            ],
            ['class MyClass3 implements A, B {}',
                ['MyClass3'=>
                    [
                        'name' => 'MyClass3',
                        'implements' => ['A', 'B']
                    ]
                ],
            ],
            ['class MyClass4 extends MyParent implements A, B {}',
                ['MyClass4'=>
                    [
                        'name' => 'MyClass4',
                        'extends' => 'MyParent',
                        'implements' => ['A', 'B']
                    ]
                ],
            ],
            [ 'class MyClass5 {
                use A;
                use B;
               }',
                ['MyClass5' =>
                    [
                        'name' => 'MyClass5',
                        'traits' => ['A', 'B']
                    ]
                ],
            ],
            [ 'class MyClass01 {
                use A;
               }
               
               class MyClass02 extends MyParent02 {}

               class MyClass03 implements A,B {}
               
               ',
                [
                 'MyClass01' =>
                    [
                        'name' => 'MyClass01',
                        'traits' => ['A']
                    ],
                 'MyClass02' =>
                    [
                        'name' => 'MyClass02',
                        'extends' => 'MyParent02'
                    ],
                 'MyClass03' =>
                    [
                        'name' => 'MyClass03',
                        'implements' => ['A','B']
                    ],
                ],
            ],

            [
                'class MyClass6 {
                    const CONST1 = 1;
                    const CONST2 = 2;
                    const CONST3 = SOME_LITERAL;
                    const CONST4 = "ABC";
                }
                
                ',
                [
                    'MyClass6' => [
                        'const' => [
                            'CONST1' => ['value' => '1'],
                            'CONST2' => ['value' => '2'],
                            'CONST3' => ['value' => 'SOME_LITERAL'],
                            'CONST4' => ['value' => '"ABC"'],
                        ]
                    ]
                ]
            ],
            [
                'class MyClass7 {
                        protected $name;
                    }

                    class MyClass8 {
                        private $attrPrivate = self::MY_ATTR;
                        protected $attrProtected=123;
                        public $attrPublic = "ABC";
                    }
                ',
                [
                    'MyClass7' => [
                        'name' => 'MyClass7',
                        'attributes' => [
                            '$name' => [
                                'scope' => 'protected',
                                'value' => null
                            ]
                        ]
                    ],
                    'MyClass8' => [
                        'name' => 'MyClass8',
                        'attributes' => [
                            '$attrPrivate' => [
                                'scope' => 'private',
                                'value' => 'self::MY_ATTR'
                            ],
                            '$attrProtected' => [
                                'scope' => 'protected',
                                'value' => '123'
                            ],
                            '$attrPublic' => [
                                'scope' => 'public',
                                'value' => '"ABC"'
                            ],
                        ]
                    ]
                ],


            ],
            [
                'class MyClass9 {

                    protected function myFunc() : string {
                        // 
                    }

                    function myFunc2() : Something { }
                }',
                [
                    'MyClass9' => [
                        'methods' => [
                            'myFunc' => [
                                'scope' => 'protected',
                                'return_type' => 'string',
                                'starting_line' => 3,
                                'ending_line' => 5
                            ],
                            'myFunc2' => [
                                'scope' => 'public',
                                'return_type' => 'Something',
                                'starting_line' => 7,
                                'ending_line' => 7
                            ]
                        ]
                    ]
                ]
            ]

        ];
    }

    /**
     *
     * @dataProvider providerGetClassDetails
     * @param [type] $statement
     * @param [type] $expectedDetails
     * @return void
     */
    public function test_get_class_details($statement, $expectedDetails)
    {
        $this->parseAndProcess($statement);


        $declaredClasses = $this->processor->getArrayDeclaredClassNames();

        foreach ($declaredClasses as $className) {
            $details = $this->processor->getAssocClass($className);
            $this->assertTrue(isset($expectedDetails[$className]));
            foreach ($expectedDetails[$className] as $key => $expectedValue) {
                $this->assertArrayHasKey($key, $details);
                $this->assertEquals($expectedValue, $details[$key]);
            }
        }
    }
}
