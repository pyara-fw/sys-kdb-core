<?php

namespace tests\unit\SysKDB\kdm\source;

use SysKDB\kdm\source\DependsOn;
use SysKDB\kdm\source\InventoryModel;
use PHPUnit\Framework\TestCase;
use SysKDB\kdm\lib\util\InventoryUtil;
use SysKDB\kdm\source\Image;

class DependenciesTest extends TestCase
{
    public function test_add_files_in_Directories()
    {
        $model = new InventoryModel();

        $root = InventoryUtil::createRootDirectoryOnModel($model);
        $folderA = InventoryUtil::createDirectoryAddOnModel($model, 'a', $root);
        $folderB = InventoryUtil::createDirectoryAddOnModel($model, 'b', $folderA);
        $folderC = InventoryUtil::createDirectoryAddOnModel($model, 'vendor', $root);
        $folderD = InventoryUtil::createDirectoryAddOnModel($model, 'public', $root);


        $file01 = InventoryUtil::createFileAddOnModel($model, 'include.php', $folderA);
        $file02 = InventoryUtil::createFileAddOnModel($model, 'logo.png', $folderB, Image::class);
        $file03 = InventoryUtil::createFileAddOnModel($model, 'logo2.png', $folderB, Image::class);
        $file04 = InventoryUtil::createFileAddOnModel($model, 'index.php', $folderD);

        InventoryUtil::addDependency($file01, $folderC);
        InventoryUtil::addDependency($file04, $file02);

        $this->assertCount(2, $folderA->getInventoryElement());
        $this->assertCount(2, $folderB->getInventoryElement());

        $this->assertCount(3, $root->getInventoryElement());

        $this->assertCount(1, $file04->getDependencies());
        $this->assertCount(0, $file02->getDependencies());
        $this->assertCount(1, $file02->getDependents());

        $this->assertCount(1, $file01->getDependencies());
        $this->assertCount(0, $folderC->getDependencies());
        $this->assertCount(1, $folderC->getDependents());
    }
}
