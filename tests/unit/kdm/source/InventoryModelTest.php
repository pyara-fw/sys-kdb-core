<?php

namespace tests\unit\SysKDB\kdm\source;

use SysKDB\kdm\source\InventoryModel;
use PHPUnit\Framework\TestCase;
use SysKDB\kdm\lib\util\InventoryUtil;
use SysKDB\kdm\source\Image;

class InventoryModelTest extends TestCase
{
    public function test_add_directories_1st_level()
    {
        $lsDirectories = ['folder1','folder2','folder3'];

        $model = new InventoryModel();

        $root = InventoryUtil::createRootDirectoryOnModel($model);

        foreach ($lsDirectories as $path) {
            InventoryUtil::createDirectoryAddOnModel($model, $path, $root);
        }
        $this->assertCount(count($lsDirectories), $root->getInventoryElement());
        $this->assertCount(count($lsDirectories)+1, $model->getInventoryElement());
    }


    public function test_add_multilevel_Directories()
    {
        $model = new InventoryModel();

        $root = InventoryUtil::createRootDirectoryOnModel($model);
        $folderA = InventoryUtil::createDirectoryAddOnModel($model, 'a', $root);
        $folderB = InventoryUtil::createDirectoryAddOnModel($model, 'b', $root);
        $folderC = InventoryUtil::createDirectoryAddOnModel($model, 'c', $folderA);
        $folderD = InventoryUtil::createDirectoryAddOnModel($model, 'd', $folderC);


        $this->assertCount(5, $model->getInventoryElement());
        $this->assertCount(1, $folderA->getInventoryElement());
        $this->assertCount(0, $folderB->getInventoryElement());
        $this->assertCount(1, $folderC->getInventoryElement());
        $this->assertCount(0, $folderD->getInventoryElement());
        $this->assertEquals($root, $folderA->getOwner());

        $rootRemoved = $model->getInventoryElement()->remove(0);

        $this->assertEquals($root, $folderA->getOwner());
        $this->assertEquals($rootRemoved, $folderA->getOwner());
        $this->assertCount(4, $model->getInventoryElement());
        $this->assertEquals('/', $root->getPath());
        $this->assertEquals('/a/', $folderA->getPath());
        $this->assertEquals('/b/', $folderB->getPath());
        $this->assertEquals('/a/c/', $folderC->getPath());
    }

    public function test_add_files_in_Directories()
    {
        $model = new InventoryModel();

        $root = InventoryUtil::createRootDirectoryOnModel($model);
        $folderA = InventoryUtil::createDirectoryAddOnModel($model, 'a', $root);
        $folderB = InventoryUtil::createDirectoryAddOnModel($model, 'b', $folderA);


        $file01 = InventoryUtil::createFileAddOnModel($model, 'include.php', $folderA);
        $file02 = InventoryUtil::createFileAddOnModel($model, 'logo.png', $folderB, Image::class);

        $this->assertCount(2, $folderA->getInventoryElement());
        $this->assertCount(1, $folderB->getInventoryElement());
        $this->assertEquals('/a/', $folderA->getPath());
        $this->assertEquals('/a/b/', $folderB->getPath());
        $this->assertEquals('/a/include.php', $file01->getPath());
        $this->assertEquals('/a/b/logo.png', $file02->getPath());
    }


    public function test_sanitize_filenames_when_adding_files()
    {
        $model = new InventoryModel();

        $root = InventoryUtil::createRootDirectoryOnModel($model);
        $folderA = InventoryUtil::createDirectoryAddOnModel($model, 'a', $root);

        $file01 = InventoryUtil::createFileAddOnModel($model, '/xpto/include.php', $folderA);

        $this->assertCount(1, $folderA->getInventoryElement());
        $this->assertEquals('/a/', $folderA->getPath());
        $this->assertEquals('/a/include.php', $file01->getPath());
    }
}
