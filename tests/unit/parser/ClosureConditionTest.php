<?php
namespace tests\unit\SysKDB\parser;

use Closure;
use PHPUnit\Framework\TestCase;
use SysKDB\parser\ClosureCondition;

class ClosureConditionTest extends TestCase {


    public function provideEvaluate() {
        return [
            [
                function ($token) {
                    return $token==='*';
                },
                '*',
                true
            ],
            [
                function ($token) {
                    return $token>10;
                },
                11,
                true
            ],
            [
                function ($token) {
                    return $token>10;
                },
                5,
                false
            ],
        ];
    }


    /**
     * @dataProvider provideEvaluate
     * @param [type] $condition
     * @param [type] $token
     * @param [type] $expectedResult
     * @return void
     */
    public function test_evaluate($condition, $token, $expectedResult)
    {
        $rule = new ClosureCondition($condition);

        $result = $rule->evaluate($token);

        $this->assertEquals($expectedResult, $result);
    }
}