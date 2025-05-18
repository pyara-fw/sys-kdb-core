<?php

namespace SysKDB\kdm\lib\util;

use SysKDB\kdm\source\AbstractInventoryElement;
use SysKDB\kdm\source\DependsOn;
use SysKDB\kdm\source\Directory;
use SysKDB\kdm\source\InventoryContainer;
use SysKDB\kdm\source\InventoryItem;
use SysKDB\kdm\source\InventoryModel;
use SysKDB\kdm\source\SourceFile;

class InventoryUtil
{
    /**
     *
     * @param InventoryModel $model
     * @param string $path
     * @return Directory
     */
    public static function createRootDirectoryOnModel(InventoryModel &$model, string $path=''): Directory
    {
        static::addTraillingSlashOnPath($path);
        $root = new Directory();
        $root->setModel($model);
        $root->setPath($path);
        $model->getInventoryElement()->add($root);
        return $root;
    }

    protected static function addTraillingSlashOnPath(string &$path)
    {
        if (substr($path, -1) != '/') {
            $path .= '/';
        }
    }


    /**
     *
     *
     * @param InventoryModel $model
     * @param string $path
     * @param InventoryContainer|null $container
     * @return Directory
     */
    public static function createDirectoryAddOnModel(InventoryModel &$model, string $path, ?InventoryContainer $container): Directory
    {
        if (!$container) {
            $container = static::createRootDirectoryOnModel($model);
        }

        static::sanitizeFileName($path);
        static::addTraillingSlashOnPath($path);

        if (is_a($container, Directory::class)) {
            $path = $container->getPath() . $path;
        }

        $folder = new Directory();
        $folder->setPath($path);
        $folder->setOwner($container);
        $container->getInventoryElement()->add($folder);

        $folder->setModel($model);
        $model->getInventoryElement()->add($folder);
        return $folder;
    }


    public static function createFileAddOnModel(InventoryModel &$model, string $fileName, InventoryContainer $container, string $className=SourceFile::class): InventoryItem
    {
        if (!$container) {
            $container = static::createRootDirectoryOnModel($model);
        }

        static::sanitizeFileName($fileName);


        if (is_a($container, Directory::class)) {
            $fileName = $container->getPath() . $fileName;
        }

        $file = new $className();
        $file->setPath($fileName);

        $file->setOwner($container);
        $container->getInventoryElement()->add($file);

        $file->setModel($model);
        $model->getInventoryElement()->add($file);


        return $file;
    }

    protected static function sanitizeFileName(string &$fileName)
    {
        $fileName = basename($fileName);
    }

    public static function addDependency(AbstractInventoryElement &$dependent, AbstractInventoryElement &$dependency)
    {
        $dependsOn = new DependsOn();
        $dependsOn->setFrom($dependent);
        $dependsOn->setTo($dependency);

        $dependent->getDependencies()->add($dependsOn);
        $dependency->getDependents()->add($dependsOn);
    }
}
