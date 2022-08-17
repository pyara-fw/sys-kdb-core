<?php
namespace tests\unit\SysKDB\kdb\processor;

require_once __DIR__.'/PHPTestBase.php';

class PHPClassesTest extends PHPTestBase {


    public function providerGetSimpleClassName() {
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
    public function test_get_simple_class_name($statement, $expectedClassName,$countExpectedClasses) {
        $this->parseAndProcess($statement);

        $this->assertEquals($expectedClassName, $this->processor->getVar('current_class_name'));        
        $this->assertCount($countExpectedClasses, $this->processor->getArray('declared_class_names',[]));
    }


    public function providerGetClassDetails() {
        return [
            ['class MyClass {}', ['MyClass'=>['name' => 'MyClass']],],
            ['abstract class MyClass extends MyParent {}', 
                ['MyClass'=>
                    [
                        'name' => 'MyClass',
                        'extends' => 'MyParent',
                        'type' => 'class',
                        'is_abstract' => true
                    ]
                ],
            ],
            ['class MyClass implements A {}', 
                ['MyClass'=>
                    [
                        'name' => 'MyClass',
                        'implements' => ['A']
                    ]
                ],
            ],
            ['class MyClass implements A, B {}', 
                ['MyClass'=>
                    [
                        'name' => 'MyClass',
                        'implements' => ['A', 'B']
                    ]
                ],
            ],
            ['class MyClass extends MyParent implements A, B {}', 
                ['MyClass'=>
                    [
                        'name' => 'MyClass',
                        'extends' => 'MyParent',
                        'implements' => ['A', 'B']
                    ]
                ],
            ],
            [ 'class MyClass {
                use A;
                use B;
               }', 
                ['MyClass' =>
                    [
                        'name' => 'MyClass',
                        'traits' => ['A', 'B']
                    ]
                ],
            ],
            [ 'class MyClass1 {
                use A;
               }
               
               class MyClass2 extends MyParent2 {}

               class MyClass3 implements A,B {}
               
               ', 
                [
                 'MyClass1' =>
                    [
                        'name' => 'MyClass1',
                        'traits' => ['A']
                    ],
                 'MyClass2' => 
                    [
                        'name' => 'MyClass2',
                        'extends' => 'MyParent2'
                    ],
                 'MyClass3' => 
                    [
                        'name' => 'MyClass3',
                        'implements' => ['A','B']
                    ],
                ],
            ],

            [
                'class MyClass {
                    const CONST1 = 1;
                    const CONST2 = 2;
                    const CONST3 = SOME_LITERAL;
                    const CONST4 = "ABC";
                }
                
                ',
                [
                    'MyClass' => [
                        'const' => [
                            'CONST1' => '1',
                            'CONST2' => '2',
                            'CONST3' => 'SOME_LITERAL',
                            'CONST4' => '"ABC"',
                        ]
                    ]
                ]
            ],
            [
                '
                    class MyClass {
                        protected $name;
                    }

                    class MyClass2 {
                        private $attrPrivate = self::MY_ATTR;
                        protected $attrProtected=123;
                        public $attrPublic = "ABC";
                    }
                ',
                [
                    'MyClass' => [
                        'name' => 'MyClass',
                        'attributes' => [
                            '$name' => [
                                'scope' => 'protected',
                                'value' => null
                            ]                            
                        ]
                    ],
                    'MyClass2' => [
                        'name' => 'MyClass2',
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
                'class MyClass {

                    protected function myFunc() : string {
                        // 
                    }
                }',
                [
                    'MyClass' => [
                        'methods' => [
                            'myFunc' => [
                                'scope' => 'protected',
                                'return_type' => 'string'
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
    public function test_get_class_details($statement, $expectedDetails) {

        $this->parseAndProcess($statement);


        $declaredClasses = $this->processor->getArray('declared_class_names',[]);

        foreach ($declaredClasses as $className) {
            $details = $this->processor->hashGet('declared_classes',$className);
            $this->assertTrue(isset($expectedDetails[$className]));
            foreach ($expectedDetails[$className] as $key => $expectedValue) {
                $this->assertArrayHasKey($key, $details);
                $this->assertEquals($expectedValue, $details[$key]);
            }
        }
    }




}