<?php

namespace tests\unit\SysKDB\kdb\processor;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Report\Xml\Source;
use SysKDB\kdb\KDB;
use SysKDB\kdb\repository\KDBRepository;
use SysKDB\kdb\repository\KDM2KDBUtil;
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

class KDM2KDBTest extends TestCase
{
    public function test1()
    {
        $inventoryModel = new InventoryModel();
        $model = new CodeModel();


        $file0 = new SourceFile();
        $file0->setModel($inventoryModel);
        $file0->setVersion('1.0.0');
        $file0->setName('OtherClass.php');
        $inventoryModel->getInventoryElement()->add($file0);

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
        $inventoryModel->getInventoryElement()->add($file);
        // $file->setOwner($inventoryModel);

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


        // $adapter = new InMemoryAdapter();

        // $repository = new KDBRepository();
        // $repository->setAdapter($adapter);

        // $list = KDM2KDBUtil::convertKDM2KDB($inventoryModel);
        // $repository->import($list);

        // print_r($list);


        // print_r($myClass->export());

        $this->assertTrue(true);
    }
}
