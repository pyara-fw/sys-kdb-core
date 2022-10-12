<?php

namespace tests\functional\SysKDB\kdb;

use PHPUnit\Framework\TestCase;
use SysKDB\kdb\KDB;
use SysKDB\kdb\repository\adapter\InMemoryAdapter;
use SysKDB\kdb\repository\adapter\MongoAdapter;
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
use SysKDB\lib\Constants;
use MongoDB\Driver\BulkWrite;
use MongoDB\Client;

/**
 * FeedMongoDBTest
 */
class FeedMongoDBTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->cleanupMongoDatabase();
    }

    protected function getClient()
    {
        if (!$this->client) {
            $mongoUsername = getenv('MONGO_USERNAME');
            $mongoPassword = getenv('MONGO_PASSWORD');
            $mongoHost = getenv('MONGO_HOST');

            $this->client = new Client("mongodb://$mongoUsername:$mongoPassword@$mongoHost:27017");
        }
        return $this->client;
    }

    protected function cleanupMongoDatabase()
    {
        $mongoDatabase = getenv('MONGO_DATABASE');
        $collection = $this->getClient()->$mongoDatabase->objects;
        $collection->deleteMany([]);
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        // $this->cleanupMongoDatabase();
    }

    public function test_dataset_1()
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


        // Starting the conversion and data persistence
        $adapter = new MongoAdapter();

        $repository = new KDBRepository();
        $repository->setAdapter($adapter);

        $list = KDM2KDBUtil::convertKDM2KDB($inventoryModel);
        $repository->import($list);

        // Query
        $ds = $repository->getAdapter()->getAll();

        $this->assertCount(10, $ds);

        $myClassOid = $myClass->getOid();
        $myClassObj = $repository->getAdapter()->getObjectById($myClassOid);

        $this->assertTrue(is_array($myClassObj));
        $this->assertArrayHasKey('name', $myClassObj);
        $this->assertEquals('MyClass', $myClassObj['name']);


        $list = $repository->getAdapter()
            ->findByKeyValueAttribute(Constants::CLASSNAME, ClassUnit::class);

        // print_r($list)            ;

        $this->assertCount(2, $list);
        $list = $list->findByKeyValueAttribute('name', 'MyClass');
        $this->assertCount(1, $list);

        print_r($list);

        // SysKDB\kdm\code\KExtends
        // from which class MyClass extends for?
        // $list = $list->findByKeyValueAttribute('name', 'MyClass');
    }
}
