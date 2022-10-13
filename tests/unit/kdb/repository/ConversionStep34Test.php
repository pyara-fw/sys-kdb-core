<?php

namespace tests\unit\SysKDB\kdb\processor;

use PhpParser\Builder\Method;
use PHPUnit\Framework\TestCase;
use SysKDB\kdb\KDB;
use SysKDB\kdb\repository\adapter\InMemoryAdapter;
use SysKDB\kdb\repository\DataSet;
use SysKDB\kdb\repository\KDBRepository;
use SysKDB\kdb\repository\util\ConvertUtil;
use SysKDB\kdm\code\ClassUnit;
use SysKDB\kdm\code\CodeModel;
use SysKDB\kdm\code\CommentUnit;
use SysKDB\kdm\code\ExportKind;
use SysKDB\kdm\code\KExtends;
use SysKDB\kdm\code\MemberUnit;
use SysKDB\kdm\code\MethodUnit;
use SysKDB\kdm\code\StringType;
use SysKDB\kdm\source\InventoryModel;
use SysKDB\kdm\source\SourceFile;
use SysKDB\kdm\source\SourceRef;
use SysKDB\kdm\source\SourceRegion;
use SysKDB\lib\Constants;

/**
 * Undocumented class
 */
class ConversionStep34Test extends TestCase
{
    /**
     * @var KDBRepository
     */
    public $repository;



    protected function populateData($assets)
    {
        $adapter = new InMemoryAdapter();
        $this->repository = new KDBRepository();
        $this->repository->setAdapter($adapter);

        foreach ($assets as $item) {
            $list = ConvertUtil::convertKDM_2_KDB($item);
            $this->repository->import($list);
        }
    }



    public function convert_kdm_to_kdb_provider()
    {
        return [
            $this->buildAssetsTest3_1(),
            $this->buildAssetsTest3_2(),
        ];
    }


    /**
     * @dataProvider convert_kdm_to_kdb_provider
     *
     * @return void
     */
    public function test_convert_kdm_to_kdb($assets, $tests)
    {
        // Starting the conversion and data persistence
        $this->populateData($assets);

        foreach ($tests as $test) {
            $test($assets, $this);
        }
    }



    public function convert_kdb_to_kdm_provider()
    {
        return [
            $this->buildAssetsTest4_1(),
        ];
    }

    /**
     * @dataProvider convert_kdb_to_kdm_provider
     *
     * @return void
     */
    public function test_convert_kdb_to_kdm($assets, $tests)
    {
        // Starting the conversion and data persistence
        $this->populateData($assets);

        $wholeDataSet = $this->repository->getAdapter()->getAll();

        $assets2 = ConvertUtil::convertKDB_2_KDM($wholeDataSet);


        foreach ($tests as $test) {
            $test($assets, $assets2, $this);
        }
    }



    // ===============================================
    // ===============================================
    // ===============================================


    protected function buildAssetsTest3_1(): array
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


        $tests = [];
        $tests[] = function ($assets, $self) {
            // Query all items. Should return 10 elements
            $ds = $self->repository->getAdapter()->getAll();
            // print_r($ds->getList());
            $self->assertCount(11, $ds);
        };

        $tests[] = function ($assets, $self) {
            // Picking an element using its ID, and comparing with the original
            $assets['myClass2.oid'] = $assets['myClass2']->getOid();
            $assets['myClass2.obj'] = $self->repository->getAdapter()->getObjectById($assets['myClass2.oid']);

            $self->assertTrue(is_array($assets['myClass2.obj']));
            $self->assertArrayHasKey('name', $assets['myClass2.obj']);
            $self->assertEquals('MyClass', $assets['myClass2.obj']['name']);
        };


        $tests[] = function ($assets, $self) {
            // Get all classes
            $listClasses = $self->repository->getAdapter()
            ->findByKeyValueAttribute(Constants::CLASSNAME, ClassUnit::class);

            $self->assertCount(2, $listClasses);

            // From previous list, filter to get only abstract classes
            $listAbstractClasses = $listClasses->findByKeyValueAttribute('isAbstract', true);
            $self->assertCount(1, $listAbstractClasses);
            $self->assertEquals($assets['myClass1']->getName(), $listAbstractClasses->get(0)['name']);
        };

        $tests[] = function ($assets, $self) {
            // Get all classes
            $listClasses = $self->repository->getAdapter()
            ->findByKeyValueAttribute(Constants::CLASSNAME, ClassUnit::class);

            // Giving a class, look for it parent, if any.
            $listSelectedClasses = $listClasses->findByKeyValueAttribute('name', 'MyClass');
            $self->assertCount(1, $listSelectedClasses);
        };

        return [$assets, $tests];
    }

    protected function buildAssetsTest3_2(): array
    {
        $assets = [];
        $tests = [];


        $assets['myClass'] = new ClassUnit();
        $assets['myClass']->setName('MyClass');

        $assets['commentUnit1'] = new CommentUnit();
        $assets['commentUnit1']->setText('// @type string');


        $assets['method1'] = new MethodUnit();
        $assets['method1']->setName('myMethod1');
        $assets['method1']->setOwner($assets['myClass']);
        $assets['method1']->setDataType(new StringType());
        $assets['method1']->setExportKind(new ExportKind(ExportKind::PROTECTED));
        $assets['method1']->getComment()->add($assets['commentUnit1']);

        $assets['myClass']->getCodeElement()->add($assets['method1']);

        $assets['method2'] = new MethodUnit();
        $assets['method2']->setName('myMethod2');
        $assets['method2']->setOwner($assets['myClass']);
        $assets['method2']->setDataType(new StringType());
        $assets['method2']->setExportKind(new ExportKind(ExportKind::PUBLIC));

        $assets['myClass']->getCodeElement()->add($assets['method2']);


        $tests[] = function ($assets, $self) {
            $listClasses = $self->repository->getAdapter()
                ->findByKeyValueAttribute(Constants::CLASSNAME, ClassUnit::class);
            $self->assertCount(1, $listClasses);
        };

        $tests[] = function ($assets, $self) {
            $listMethods = $self->repository->getAdapter()
                ->findByKeyValueAttribute(Constants::CLASSNAME, MethodUnit::class);
            $self->assertCount(2, $listMethods);
        };


        return [$assets, $tests];
    }


    public function buildAssetsTest4_1(): array
    {
        $assets = [];
        $tests = [];


        $assets['myClass'] = new ClassUnit();
        $assets['myClass']->setName('MyClass');

        $assets['commentUnit1'] = new CommentUnit();
        $assets['commentUnit1']->setText('// @type string');


        $assets['method1'] = new MethodUnit();
        $assets['method1']->setName('myMethod1');
        $assets['method1']->setOwner($assets['myClass']);
        $assets['method1']->setDataType(StringType::getInstance());
        $assets['method1']->setExportKind(new ExportKind(ExportKind::PROTECTED));
        $assets['method1']->getComment()->add($assets['commentUnit1']);

        $assets['myClass']->getCodeElement()->add($assets['method1']);

        $assets['method2'] = new MethodUnit();
        $assets['method2']->setName('myMethod2');
        $assets['method2']->setOwner($assets['myClass']);
        $assets['method2']->setDataType(StringType::getInstance());
        $assets['method2']->setExportKind(new ExportKind(ExportKind::PUBLIC));

        $assets['myClass']->getCodeElement()->add($assets['method2']);

        $tests[] = function ($originalAssets, $finalAssets, $self) {
            // All original assets should be present on finalAssets list
            foreach ($originalAssets as $k => $assetItem) {
                $oid = $assetItem->getOid();
                $self->assertTrue(isset($finalAssets[$oid]));
            }
        };

        $tests[] = function ($originalAssets, $finalAssets, $self) {
            // Find the class with name MyClass
            $dataSet = new DataSet();
            foreach ($finalAssets as $item) {
                $dataSet->add($item);
            }
            $selectedClass = $dataSet->findByKeyValueAttribute('name', 'MyClass');
            $self->assertTrue(!empty($selectedClass));
            $self->assertTrue(is_object($selectedClass));
        };

        return [$assets, $tests];
    }
}
