<?php

namespace tests\unit\SysKDB\kdb\processor;

use PHPUnit\Framework\TestCase;
use SysKDB\kdb\processor\PHP;


class PHPTestBase extends TestCase {


    public $processor; 

    protected function setUp(): void
    {
        parent::setUp();
        $this->processor = new PHP();
        
    }
    

    protected function parseAndProcess($statement) {
        $contents = '<?php ' . $statement;
        $tokens = $this->processor->parse($contents);
        $this->processor->processTokens($tokens);
    }


}