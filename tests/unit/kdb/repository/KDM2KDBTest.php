<?php

namespace tests\unit\SysKDB\kdb\processor;

use PHPUnit\Framework\TestCase;
use SysKDB\kdb\KDB;
use SysKDB\kdb\repository\adapter\InMemoryAdapter;
use SysKDB\kdb\repository\KDBRepository;
use SysKDB\kdb\repository\util\ConvertUtil;
use SysKDB\kdm\code\ClassUnit;
use SysKDB\kdm\code\CodeModel;
use SysKDB\kdm\code\CommentUnit;
use SysKDB\kdm\code\ExportKind;
use SysKDB\kdm\code\KExtends;
use SysKDB\kdm\code\MemberUnit;
use SysKDB\kdm\code\StringType;
use SysKDB\kdm\source\InventoryModel;
use SysKDB\kdm\source\SourceFile;
use SysKDB\kdm\source\SourceRef;
use SysKDB\kdm\source\SourceRegion;
use SysKDB\lib\Constants;

class KDM2KDBTest extends TestCase
{
    protected $repository;


    protected function buildAssetsTest1(): array
    {
        $assets = [];
        $assets['inventoryModel'] = new InventoryModel();
        $assets['model'] = new CodeModel();


        $assets['file1'] = new SourceFile();
        $assets['file1']->setModel($assets['inventoryModel']);
        $assets['file1']->setVersion('1.0.0');
        $assets['file1']->setName('OtherClass.php');
        $assets['inventoryModel']->getInventoryElement()->add($assets['file1']);

        $assets['sourceRegion1'] = new SourceRegion();
        $assets['sourceRegion1']->setFile($assets['file1']);


        $assets['source1'] = new SourceRef();
        $assets['source1']->setSnippet('...');

        $assets['sourceRegion1']->setSourceRef($assets['source1']);


        $assets['myClass1'] = new ClassUnit();
        $assets['myClass1']->setName('OtherClass');
        $assets['myClass1']->setOwner($assets['file1']);
        $assets['myClass1']->setModel($assets['model']);
        $assets['myClass1']->setIsAbstract(true);


        $assets['file2'] = new SourceFile();
        $assets['file2']->setModel($assets['inventoryModel']);
        $assets['file2']->setVersion('1.0.0');
        $assets['file2']->setName('MyClass.php');
        $assets['inventoryModel']->getInventoryElement()->add($assets['file2']);

        $assets['sourceRegion2'] = new SourceRegion();
        $assets['sourceRegion2']->setFile($assets['file2']);


        $assets['source2'] = new SourceRef();
        $assets['source2']->setSnippet('...');

        $assets['sourceRegion2']->setSourceRef($assets['source2']);


        $assets['myClass2'] = new ClassUnit();
        $assets['myClass2']->setName('MyClass');

        $assets['myClass2']->setOwner($assets['file2']);
        $assets['myClass2']->setModel($assets['model']);

        $assets['classExtension'] = new KExtends($assets['myClass2'], $assets['myClass1']);

        $assets['commentUnit1'] = new CommentUnit();
        $assets['commentUnit1']->setText('// @type string');

        $assets['memberUnit1'] = new MemberUnit();
        $assets['memberUnit1']->setName('myCode');
        $assets['memberUnit1']->setType(new StringType());
        $assets['memberUnit1']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['memberUnit1']->getComment()->add($assets['commentUnit1']);

        $assets['myClass2']->getCodeElement()->add($assets['memberUnit1']);
        return $assets;
    }

    protected function populateData($assets)
    {
        $adapter = new InMemoryAdapter();
        $this->repository = new KDBRepository();
        $this->repository->setAdapter($adapter);


        $list = ConvertUtil::convertKDM_2_KDB($assets['inventoryModel']);
        $this->repository->import($list);


        $list2 = ConvertUtil::convertKDM_2_KDB($assets['model']);
        $this->repository->import($list2);
    }


    public function test1()
    {
        $assets = $this->buildAssetsTest1();

        // Starting the conversion and data persistence
        $this->populateData($assets);


        // Query all items. Should return 10 elements
        $ds = $this->repository->getAdapter()->getAll();
        $this->assertCount(10, $ds);

        // Picking an element using its ID, and comparing with the original
        $assets['myClass2.oid'] = $assets['myClass2']->getOid();
        $assets['myClass2.obj'] = $this->repository->getAdapter()->getObjectById($assets['myClass2.oid']);

        $this->assertTrue(is_array($assets['myClass2.obj']));
        $this->assertArrayHasKey('name', $assets['myClass2.obj']);
        $this->assertEquals('MyClass', $assets['myClass2.obj']['name']);



        // Get all classes
        $listClasses = $this->repository->getAdapter()
            ->findByKeyValueAttribute(Constants::CLASSNAME, ClassUnit::class);

        $this->assertCount(2, $listClasses);

        // From previous list, filter to get only abstract classes
        $listAbstractClasses = $listClasses->findByKeyValueAttribute('isAbstract', true);
        $this->assertCount(1, $listAbstractClasses);
        $this->assertEquals($assets['myClass1']->getName(), $listAbstractClasses->get(0)['name']);

        // print_r($listClasses);

        // Giving a class, look for it parent, if any.
        $listSelectedClasses = $listClasses->findByKeyValueAttribute('name', 'MyClass');
        $this->assertCount(1, $listSelectedClasses);
    }


    /**
     * @depends test1
     *
     * @return void
     */
    public function test2()
    {
        $assets = $this->buildAssetsTest1();

        // Starting the conversion and data persistence
        $this->populateData($assets);

        KDB::getInstance()->setRepository($this->repository);

        $myClassObj = KDB::getInstance()->getClassByName('MyClass');
        // print_r($myClassObj);
        $this->assertTrue(true);
    }
}
