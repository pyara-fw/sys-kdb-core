<?php
namespace tests\unit\SysKDB\parser;

use PHPUnit\Framework\TestCase;
use SysKDB\parser\RuleCondition;

class RuleConditionTest extends TestCase   {


    public function provideEvaluate() {
        return [
            ['true', '', true],
            ['$token=="*"', '*', true],
            ['$token=="*"', '-', false],
            ['$token[0]===T_NAMESPACE', [T_NAMESPACE], true],
            ['@$abc==="a"', [''], false],
            ['@$abc!=="a"', [''], true],
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
        $rule = new RuleCondition($condition);

        $result = $rule->evaluate($token);

        $this->assertEquals($expectedResult, $result);
    }
    

}