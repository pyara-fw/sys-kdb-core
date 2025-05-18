<?php

use PHPUnit\Framework\TestCase;
use SysKDB\kdb\processor\PHP;

class PHPTest extends TestCase {


    public function providerAssignLineNumber() {
        return [

            [
                "",
                ""
            ],
            [
                "Line 1
Line 2
Line 3",
                "Line 1
Line 2
Line 3"
            ],
            [
                "<?php // line 1
                // line 2
                // line 3",
                "<?php\t'[<000001>]'\t // line 1
\t'[<000002>]'\t                // line 2
\t'[<000003>]'\t                // line 3"
            ],
            [
                "<?php // line 1
                // line 2
                // line 3
?> ignore
ignoring this line

<?php // line 7",
                "<?php\t'[<000001>]'\t // line 1
\t'[<000002>]'\t                // line 2
\t'[<000003>]'\t                // line 3
\t'[<000004>]'\t?> ignore
ignoring this line

<?php\t'[<000007>]'\t // line 7"
            ],           

        ];
    }



    /**
     * 
     * @dataProvider providerAssignLineNumber
     * @return void
     */
    public function test_assignLineNumber($contents, $expected) {
        $processor = new PHP();
        $result = $processor->assignLineNumber($contents);
        $this->assertEquals($expected, $result);
    }
}