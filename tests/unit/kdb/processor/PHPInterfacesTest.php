<?php
namespace tests\unit\SysKDB\kdb\processor;

require_once __DIR__.'/PHPTestBase.php';

class PHPInterfacesTest extends PHPTestBase {

    /**
     * 
     *
     * @return array
     */
    public function providerGetInterfaceDetails() : array {
        return [
            [
                'interface MyInterface {}', 
                [
                    'MyInterface'=> [
                        'name' => 'MyInterface',
                        'type'=>'interface'
                    ]
                ],
            ],

            [ 'class MyClass1 {
                use A;
               }
               
               interface MyInterface {

                    public function add($a,$b): int;
               }

               class MyClass3 implements A,B {}
               
               ', 
                [
                 'MyClass1' =>
                    [
                        'name' => 'MyClass1',
                        'traits' => ['A']
                    ],
                 'MyInterface' => 
                    [
                        'name' => 'MyInterface',
                        'type' => 'interface',
                        'methods' => [
                            'add' => [
                                'scope' => 'public',
                                'return_type' => 'int',
                                'starting_line' => 7,
                                'ending_line' => 7
                            ]
                        ]
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

                    protected function myFunc() : string {
                        // 
                    }
                }',
                [
                    'MyClass' => [
                        'methods' => [
                            'myFunc' => [
                                'scope' => 'protected',
                                'return_type' => 'string',
                                'starting_line' => 3,
                                'ending_line' => 5
                            ]
                        ]
                    ]
                ]    
            ]

        ];
    }

    /**
     * 
     * @dataProvider providerGetInterfaceDetails
     * @param [type] $statement
     * @param [type] $expectedDetails
     * @return void
     */
    public function test_get_interface_details($statement, $expectedDetails) {

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