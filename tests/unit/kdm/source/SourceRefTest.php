<?php

namespace tests\unit\SysKDB\kdm\source;

use PHPUnit\Framework\TestCase;
use SysKDB\kdm\lib\Constants;
use SysKDB\kdm\source\InventoryModel;
use SysKDB\kdm\source\SourceFile;
use SysKDB\kdm\source\SourceRef;
use SysKDB\kdm\source\SourceRegion;

class SourceRefTest extends TestCase
{
    public function test_SourceRef_snippet_has_multiple_SourceRegions()
    {
        $snippet = <<<END
        function getAddress() {                 // line 5
            // retrieve the user's address;
            return \$address;
        }                                       // line 15

        function getPostalCode() {              // line 40

            \$sql = "SELECT postal_code 
                     FROM user 
                     WHERE user_id = :user_id "; // line 50

            // retrieve the user's postal code;            
            return \$postalCode;
        }                                       // line 80
        
END;


        $file = new SourceFile();

        $sourceRef = new SourceRef();
        $sourceRef->setSnippet($snippet);
        $sourceRef->setLanguage(Constants::LANGUAGE_PHP);


        $sourceRegion01 = new SourceRegion();
        $sourceRegion01->setSourceRef($sourceRef)
                       ->setStartLine(5)
                       ->setEndLine(15)
                       ->setLanguage(Constants::LANGUAGE_PHP)
                       ->setFile($file);

        $sourceRegion02 = new SourceRegion();
        $sourceRegion02->setSourceRef($sourceRef)
                       ->setStartLine(40)
                       ->setEndLine(80)
                       ->setLanguage(Constants::LANGUAGE_PHP);

        $sourceRegion03 = new SourceRegion();
        $sourceRegion03->setSourceRef($sourceRef)
                       ->setStartLine(48)
                       ->setEndLine(50)
                       ->setLanguage(Constants::LANGUAGE_SQL);

        $sourceRef->getSourceRegions()->add($sourceRegion01);
        $sourceRef->getSourceRegions()->add($sourceRegion02);
        $sourceRef->getSourceRegions()->add($sourceRegion03);

        $this->assertCount(3, $sourceRef->getSourceRegions());
    }
}
