<?php

namespace tests\unit\Pyara\app\generator;

use PHPUnit\Framework\TestCase;
use Pyara\app\generator\providers\PlantUML;
use SysKDB\kdb\repository\adapter\InMemoryAdapter;
use SysKDB\kdb\repository\DataSet;
use SysKDB\kdb\repository\KDBRepository;
use SysKDB\kdb\repository\util\ConvertUtil;
use SysKDB\kdb\repository\util\KDB2KDMUtil;
use SysKDB\kdm\code\ClassUnit;
use SysKDB\kdm\code\CommentUnit;
use SysKDB\kdm\code\ExportKind;
use SysKDB\kdm\code\IntegerType;
use SysKDB\kdm\code\InterfaceUnit;
use SysKDB\kdm\code\KExtends;
use SysKDB\kdm\code\Kimplements;
use SysKDB\kdm\code\KinstanceOf;
use SysKDB\kdm\code\MemberUnit;
use SysKDB\kdm\code\MethodKind;
use SysKDB\kdm\code\MethodUnit;
use SysKDB\kdm\code\StringType;
use SysKDB\kdm\code\TemplateUnit;
use SysKDB\kdm\core\KDMRelationship;
use SysKDB\kdm\kdm\Attribute;

/**
 * TODO
 * [x] Simple class
 * [x] Two classes, with inheritance
 * [x] Abstract class
 * [x] Two classes, and one implementing an interface
 * [ ] A class associated to another
 *
 */
class PlantUmlGeneratorsTest extends TestCase
{
    protected $repository;
    protected $inputAssets;


    public function generate_simple_class_diagram_provider()
    {
        return [
            $this->buildAssets01(),
            $this->buildAssets02(),
            $this->buildAssets03(),
            $this->buildAssets04(),
        ];
    }


    /**
     *
     * @dataProvider generate_simple_class_diagram_provider
     * @param [type] $assets
     * @param [type] $tests
     * @return void
     */
    public function test_generate_simple_class_diagram($assets, $tests)
    {
        ConvertUtil::reset();


        $this->populateData($assets);

        $this->assertTrue(is_array($this->inputAssets));

        // Do the same with data converted from KDB to KDM format as well.
        $wholeDataSet = $this->repository->getAdapter()->getAll();
        $posProcessedAssets = ConvertUtil::convertKDB_2_KDM($wholeDataSet);

        $this->assertTrue(is_array($posProcessedAssets));

        $generator = new PlantUML();


        // Testing all post-processed items - primary cases
        // Since most of cases will be of records from KDB, this is
        // the primary case.

        $outputPos = $generator->generateClassDiagram(new DataSet($posProcessedAssets));

        foreach ($tests as $test) {
            $test($outputPos, $posProcessedAssets, $this);
        }


        // Testing all original items - secondary case
        // Since most of data won't come directly from KDM, this
        // is considered a secondary case.
        $outputPre = $generator->generateClassDiagram(new DataSet($this->inputAssets));

        foreach ($tests as $test) {
            $test($outputPre, $this->inputAssets, $this);
        }
    }



    public function buildAssets01(): array
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
        $assets['method2']->setKind(new MethodKind(MethodKind::ABSTRACT));
        $assets['method2']->setDataType(StringType::getInstance());
        $assets['method2']->setExportKind(new ExportKind(ExportKind::PUBLIC));

        $assets['myClass']->getCodeElement()->add($assets['method2']);

        // ==================
        // Tests section
        // ------------------

        $tests[] = function ($output, $assets, $self) {
            $self->assertStringContainsString('class MyClass {', $output);
            $self->assertStringContainsString('# String myMethod1()', $output);
            $self->assertStringContainsString('{abstract} + String myMethod2()', $output);
            $self->assertStringContainsString('}', $output);
        };


        return [$assets, $tests];
    }

    public function buildAssets02(): array
    {
        $assets = [];
        $tests = [];


        $assets['parentClass'] = new ClassUnit();
        $assets['parentClass']->setName('MyParentClass');
        $assets['parentClass']->setIsAbstract(true);

        $assets['commentUnit1'] = new CommentUnit();
        $assets['commentUnit1']->setText('// @type string');

        $assets['attribute1'] = new MemberUnit();
        $assets['attribute1']->setName('tag');
        $assets['attribute1']->setType(new StringType());
        $assets['attribute1']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute1']->getComment()->add($assets['commentUnit1']);
        $assets['attribute1']->setOwner($assets['parentClass']);


        $assets['commentUnit2'] = new CommentUnit();
        $assets['commentUnit2']->setText('/'."* \n * It is a location code. \n *".'/');

        $assets['attribute2'] = new MemberUnit();
        $assets['attribute2']->setName('location');
        $assets['attribute2']->setType(new StringType());
        $assets['attribute2']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute2']->getComment()->add($assets['commentUnit2']);
        $assets['attribute2']->setOwner($assets['parentClass']);

        $assets['myClass'] = new ClassUnit();
        $assets['myClass']->setName('MyClass2');

        $assets['myClass_extends_parentClass'] = new KExtends();
        $assets['myClass_extends_parentClass']->setChild($assets['myClass']);
        $assets['myClass_extends_parentClass']->setParent($assets['parentClass']);


        $assets['method1'] = new MethodUnit();
        $assets['method1']->setName('myMethod1');
        $assets['method1']->setOwner($assets['myClass']);
        $assets['method1']->setDataType(StringType::getInstance());
        $assets['method1']->setExportKind(new ExportKind(ExportKind::PROTECTED));

        $assets['myClass']->getCodeElement()->add($assets['method1']);

        $assets['method2'] = new MethodUnit();
        $assets['method2']->setName('myMethod2');
        $assets['method2']->setOwner($assets['myClass']);
        $assets['method2']->setDataType(StringType::getInstance());
        $assets['method2']->setExportKind(new ExportKind(ExportKind::PUBLIC));

        $assets['myClass']->getCodeElement()->add($assets['method2']);

        // ==================
        // Tests section
        // ------------------

        $tests[] = function ($output, $assets, $self) {
            $self->assertStringContainsString('abstract class MyParentClass {', $output);
            $self->assertStringContainsString('# String tag', $output);
            $self->assertStringContainsString('# String location', $output);

            $self->assertStringContainsString('class MyClass2 extends MyParentClass {', $output);
            $self->assertStringContainsString('# String myMethod1()', $output);
            $self->assertStringContainsString('+ String myMethod2()', $output);
        };


        return [$assets, $tests];
    }

    public function buildAssets03(): array
    {
        $assets = [];
        $tests = [];




        $assets['interface1'] = new InterfaceUnit();
        $assets['interface1']->setName('MyFirstInterface');

        $assets['interface1.method1'] = new MethodUnit();
        $assets['interface1.method1']->setName('myInterfaceMethod1');
        $assets['interface1.method1']->setOwner($assets['interface1']);
        $assets['interface1.method1']->setDataType(new IntegerType());
        $assets['interface1.method1']->setExportKind(new ExportKind(ExportKind::PUBLIC));

        $assets['interface1']->getCodeElement()->add($assets['interface1.method1']);

        $assets['interface2'] = new InterfaceUnit();
        $assets['interface2']->setName('MySecondInterface');

        $assets['interface3'] = new InterfaceUnit();
        $assets['interface3']->setName('MyThirdInterface');

        $assets['MySecondInterface_extends_MyThirdInterface'] = new KExtends();
        $assets['MySecondInterface_extends_MyThirdInterface']->setChild($assets['interface3']);
        $assets['MySecondInterface_extends_MyThirdInterface']->setParent($assets['interface2']);


        $assets['parentClass'] = new ClassUnit();
        $assets['parentClass']->setName('MyParentClass');
        $assets['parentClass']->setIsAbstract(true);

        $assets['commentUnit1'] = new CommentUnit();
        $assets['commentUnit1']->setText('// @type string');

        $assets['attribute1'] = new MemberUnit();
        $assets['attribute1']->setName('tag');
        $assets['attribute1']->setType(new StringType());
        $assets['attribute1']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute1']->getComment()->add($assets['commentUnit1']);
        $assets['attribute1']->setOwner($assets['parentClass']);


        $assets['commentUnit2'] = new CommentUnit();
        $assets['commentUnit2']->setText('/'."* \n * It is a location code. \n *".'/');

        $assets['attribute2'] = new MemberUnit();
        $assets['attribute2']->setName('location');
        $assets['attribute2']->setType(new StringType());
        $assets['attribute2']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute2']->getComment()->add($assets['commentUnit2']);
        $assets['attribute2']->setOwner($assets['parentClass']);

        $assets['myClass'] = new ClassUnit();
        $assets['myClass']->setName('MyClass2');

        $assets['myClass_extends_parentClass'] = new KExtends();
        $assets['myClass_extends_parentClass']->setChild($assets['myClass']);
        $assets['myClass_extends_parentClass']->setParent($assets['parentClass']);

        $assets['myClass_implements_interface1'] = new Kimplements();
        $assets['myClass_implements_interface1']->setFrom($assets['myClass']);
        $assets['myClass_implements_interface1']->setTo($assets['interface1']);

        $assets['myClass_implements_interface2'] = new Kimplements();
        $assets['myClass_implements_interface2']->setFrom($assets['myClass']);
        $assets['myClass_implements_interface2']->setTo($assets['interface2']);

        $assets['method1'] = new MethodUnit();
        $assets['method1']->setName('myMethod1');
        $assets['method1']->setOwner($assets['myClass']);
        $assets['method1']->setDataType(StringType::getInstance());
        $assets['method1']->setExportKind(new ExportKind(ExportKind::PROTECTED));

        $assets['myClass']->getCodeElement()->add($assets['method1']);

        $assets['method2'] = new MethodUnit();
        $assets['method2']->setName('myMethod2');
        $assets['method2']->setOwner($assets['myClass']);
        $assets['method2']->setDataType(StringType::getInstance());
        $assets['method2']->setExportKind(new ExportKind(ExportKind::PUBLIC));

        $assets['myClass']->getCodeElement()->add($assets['method2']);

        // ==================
        // Tests section
        // ------------------

        $tests[] = function ($output, $assets, $self) {
            $self->assertStringContainsString('interface MyFirstInterface', $output);
            $self->assertStringContainsString('MyFirstInterface : + Integer myInterfaceMethod1()', $output);
            $self->assertStringContainsString('MyFirstInterface <|.. MyClass2', $output);
            $self->assertStringContainsString('MySecondInterface <|.. MyClass2', $output);

            $self->assertStringContainsString('interface MySecondInterface', $output);
            $self->assertStringContainsString('interface MyThirdInterface', $output);

            $self->assertStringContainsString('abstract class MyParentClass', $output);
            $self->assertStringContainsString('# String tag', $output);
            $self->assertStringContainsString('# String location', $output);

            $self->assertStringContainsString('class MyClass2 extends MyParentClass', $output);
            $self->assertStringContainsString('# String myMethod1()', $output);
            $self->assertStringContainsString('+ String myMethod2()', $output);

            // echo "\n\n$output\n\n";
        };


        return [$assets, $tests];
    }


    protected function populateData($assets)
    {
        $this->inputAssets = $assets;

        $adapter = new InMemoryAdapter();
        $this->repository = new KDBRepository();
        $this->repository->setAdapter($adapter);

        foreach ($assets as $item) {
            $list = ConvertUtil::convertKDM_2_KDB($item);
            $this->repository->import($list);
        }
    }



    public function buildAssets04(): array
    {
        $assets = [];
        $tests = [];

        $assets['classPart'] = new ClassUnit();
        $assets['classPart']->setName('Part');


        $assets['classVehicle'] = new ClassUnit();
        $assets['classVehicle']->setName('Vehicle');
        $assets['classVehicle']->setIsAbstract(true);

        $assets['classCar'] = new ClassUnit();
        $assets['classCar']->setName('Car');

        $assets['classCar_extends_classVehicle'] = new KExtends();
        $assets['classCar_extends_classVehicle']->setChild($assets['classCar']);
        $assets['classCar_extends_classVehicle']->setParent($assets['classVehicle']);

        $assets['attribute1'] = new MemberUnit();
        $assets['attribute1']->setName('part');
        $assets['attribute1']->setType($assets['classPart']);
        $assets['attribute1']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute1']->setOwner($assets['classCar']);



        $assets['attribute2'] = new MemberUnit();
        $assets['attribute2']->setName('externalReference');

        $externalAttribute = new TemplateUnit();
        $externalAttribute->setName('ExternalClass');

        $assets['attribute2']->setType($externalAttribute);
        $assets['attribute2']->setExport(new ExportKind(ExportKind::PRIVATE));
        $assets['attribute2']->setOwner($assets['classCar']);


        $assets['classEntity'] = new ClassUnit();
        $assets['classEntity']->setName('Entity');


        $assets['attribute3'] = new MemberUnit();
        $assets['attribute3']->setName('name');
        $assets['attribute3']->setType(StringType::getInstance());
        $assets['attribute3']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute3']->setOwner($assets['classEntity']);


        $assets['classDriver'] = new ClassUnit();
        $assets['classDriver']->setName('Driver');

        $assets['classOwner'] = new ClassUnit();
        $assets['classOwner']->setName('Owner');

        $assets['classDriver_extends_classEntity'] = new KExtends();
        $assets['classDriver_extends_classEntity']->setChild($assets['classDriver']);
        $assets['classDriver_extends_classEntity']->setParent($assets['classEntity']);

        $assets['classOwner_extends_classEntity'] = new KExtends();
        $assets['classOwner_extends_classEntity']->setChild($assets['classOwner']);
        $assets['classOwner_extends_classEntity']->setParent($assets['classEntity']);



        $assets['attribute4'] = new MemberUnit();
        $assets['attribute4']->setName('owner');
        $assets['attribute4']->setType($assets['classOwner']);
        $assets['attribute4']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute4']->setOwner($assets['classCar']);

        $assets['attribute5'] = new MemberUnit();
        $assets['attribute5']->setName('driver');
        $assets['attribute5']->setType($assets['classDriver']);
        $assets['attribute5']->setExport(new ExportKind(ExportKind::PROTECTED));
        $assets['attribute5']->setOwner($assets['classCar']);


        $assets['classRide'] = new ClassUnit();
        $assets['classRide']->setName('Ride');

        $tpl = new TemplateUnit();

        $atr = new Attribute();
        $atr->setTag('cardinality');
        $atr->setValue('1');
        $tpl->getAttributes()->add($atr);

        $atr = new Attribute();
        $atr->setTag('formatEndpoint');
        $atr->setValue('diamond');
        $tpl->getAttributes()->add($atr);


        $tpl->setName($assets['classRide']->getName());
        $tpl->setOwner($assets['classRide']);

        $rel = new KDMRelationship();
        // $rel->setFrom($tpl);
        $rel->setFrom($assets['classRide']);
        $rel->setTo($assets['classDriver']);
        $assets['classRide_association_classDriver'] = $rel;

        // ==================
        // Tests section
        // ------------------

        $tests[] = function ($output, $assets, $self) {
            $self->assertStringContainsString('class Part {', $output);

            $self->assertStringContainsString('abstract class Vehicle', $output);

            $self->assertStringContainsString('class Car extends Vehicle', $output);
            $self->assertStringContainsString('# Part part', $output);

            $self->assertStringContainsString('Car -- "part" Part', $output);
            $self->assertStringContainsString('- SysKDB\kdm\code\TemplateUnit externalReference', $output);

            // echo "\n\n$output\n\n";
        };


        return [$assets, $tests];
    }
}
