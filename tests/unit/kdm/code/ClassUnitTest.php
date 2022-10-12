<?php

namespace tests\unit\SysKDB\kdm\source;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Report\Xml\Source;
use SysKDB\kdm\code\CallableUnit;
use SysKDB\kdm\code\ClassUnit;
use SysKDB\kdm\code\CodeItem;
use SysKDB\kdm\code\CodeModel;
use SysKDB\kdm\code\CommentUnit;
use SysKDB\kdm\code\CompilationUnit;
use SysKDB\kdm\code\ExportKind;
use SysKDB\kdm\code\FloatType;
use SysKDB\kdm\code\KExtends;
use SysKDB\kdm\code\MemberUnit;
use SysKDB\kdm\code\MethodKind;
use SysKDB\kdm\code\MethodUnit;
use SysKDB\kdm\code\ParameterUnit;
use SysKDB\kdm\code\Signature;
use SysKDB\kdm\code\StringType;
use SysKDB\kdm\source\InventoryModel;
use SysKDB\kdm\source\SourceFile;
use SysKDB\kdm\source\SourceRef;
use SysKDB\kdm\source\SourceRegion;

class ClassUnitTest extends TestCase
{
    /**
     *

class MyClass extends OtherClass {

    // @type string
    protected $myCode;

}



     *
     * @return void
     */
    public function test2()
    {
        $inventoryModel = new InventoryModel();
        $model = new CodeModel();


        $file0 = new SourceFile();
        $file0->setModel($inventoryModel);
        $file0->setVersion('1.0.0');
        $file0->setName('OtherClass.php');

        $sourceRegion0 = new SourceRegion();
        $sourceRegion0->setFile($file0);


        $source000 = new SourceRef();
        $source000->setSnippet('...');

        $sourceRegion0->setSourceRef($source000);



        $myClass0 = new ClassUnit();
        $myClass0->setName('OtherClass');
        $myClass0->setOwner($file0);
        $myClass0->setModel($model);



        $file = new SourceFile();
        $file->setModel($inventoryModel);
        $file->setVersion('1.0.0');
        $file->setName('MyClass.php');

        $sourceRegion = new SourceRegion();
        $sourceRegion->setFile($file);


        $source00 = new SourceRef();
        $source00->setSnippet('...');

        $sourceRegion->setSourceRef($source00);


        $myClass = new ClassUnit();
        $myClass->setName('MyClass');

        $myClass->setOwner($file);
        $myClass->setModel($model);

        $classExtension = new KExtends($myClass, $myClass0);

        $commentUnit1 = new CommentUnit();
        $commentUnit1->setText('// @type string');

        $memberUnit = new MemberUnit();
        $memberUnit->setName('myCode');
        $memberUnit->setType(new StringType());
        $memberUnit->setExport(new ExportKind(ExportKind::PROTECTED));
        $memberUnit->getComment()->add($commentUnit1);

        $myClass->getCodeElement()->add($memberUnit);





        // print_r($myClass->export());

        $this->assertTrue(true);
    }



    /**
     *

class MyClass {

    // @type string
    protected $myCode;

    public function getMyCode() : string {
        return $this->myCode;
    }

    public function setMyCode(string $code) : self {
        $this->myCode = $code;
    }

}



     *
     * @return void
     */
    public function _test1()
    {
        $inventoryModel = new InventoryModel();
        $model = new CodeModel();


        $file = new SourceFile();
        $file->setModel($inventoryModel);
        $file->setVersion('1.0.0');
        $file->setName('MyClass.php');

        $sourceRegion = new SourceRegion();
        $sourceRegion->setFile($file);


        $source00 = new SourceRef();
        $source00->setSnippet('...');

        $sourceRegion->setSourceRef($source00);


        $myClass = new ClassUnit();
        $myClass->setName('MyClass');

        $myClass->setOwner($file);
        $myClass->setModel($model);


        $commentUnit1 = new CommentUnit();
        $commentUnit1->setText('// @type string');

        $memberUnit = new MemberUnit();
        $memberUnit->setName('myCode');
        $memberUnit->setType(new StringType());
        $memberUnit->setExport(new ExportKind(ExportKind::PROTECTED));
        $memberUnit->getComment()->add($commentUnit1);


        $methodUnit1 = new MethodUnit();
        $methodUnit1->setName('getMyCode');
        $methodUnit1->setDataType(new StringType());
        $methodUnit1->setExportKind(new ExportKind(ExportKind::PUBLIC));
        $methodUnit1->setKind(new MethodKind(MethodKind::METHOD));

        $source1 = new SourceRef();
        $source1->setSnippet('return $this->myCode;');
        $methodUnit1->setSource($source1);

        $methodUnit2 = new MethodUnit();
        $methodUnit2->setName('setMyCode');
        $methodUnit2->setExportKind(new ExportKind(ExportKind::PUBLIC));
        $methodUnit2->setKind(new MethodKind(MethodKind::METHOD));
        $methodUnit2->setDataType($myClass);

        $source2 = new SourceRef();
        $source2->setSnippet('$this->myCode = $code;');
        $methodUnit2->setSource($source2);


        $s = new Signature();
        $p = new ParameterUnit();
        $p->setName('code');
        $p->setType(new StringType());
        $p->setOwner($s);
        $s->setOwner($methodUnit2);


        $myClass->getCodeElement()->add($memberUnit);
        $myClass->getCodeElement()->add($methodUnit1);
        $myClass->getCodeElement()->add($methodUnit2);

        // $myClass->setModel($model);

        print_r($myClass->export());

        $this->assertTrue(true);
    }




    public function x_test_model_class()
    {
        $model = new CodeModel();

        $myClass01 = new ClassUnit();

        $myClass01->setIsAbstract(true);

        // $attribute01 = new Attribute();
        // $attribute01->setTag('name');

        // $ci01 = new CodeItem();
        // $ci01->setName('name');

        $attrName = new StringType();
        $attrName->setName('name');



        $myClass01->getCodeElement()->add($attrName);

        $model->getOwnedElements()->add($myClass01);

        // print_r($model->export());

        $this->assertTrue(true);
    }
}
