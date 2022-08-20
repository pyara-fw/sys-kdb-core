<?php
namespace tests\unit\SysKDB\kdb\processor;

require_once __DIR__.'/PHPTestBase.php';

class PHPClassMethodsTest extends PHPTestBase
{

    public function providerClassMethods() {
        return [
            [
                'class MyClass {

                    protected function myFunc() : string {
                        ClassName::staticMethod();
                        $object::staticMethod();
                        $object->normalMethod();
                        externalFunction();
                    }
                }',
                [
                    'MyClass' => [
                        'methods' => [
                            'myFunc' => [
                                'scope' => 'protected',
                                'return_type' => 'string',
                                'starting_line' => 3,
                                'ending_line' => 8,
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
                                    ]
                                ]
                            ],
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
    public function test_class_methods(string $statement, array $expectedDetails) {
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

    



