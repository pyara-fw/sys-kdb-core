<?php

namespace SysKDB\kdb\repository\util;

use SysKDB\kdb\repository\DataSet;
use SysKDB\kdm\core\Element;

class KDB2KDMUtil
{
    public const PROCESSED = '_PROCESSED_';
    public const PROCESSING = '_PROCESSING_';

    protected static $result = [];
    protected static $input = [];


    public static function reset()
    {
        static::$result = [];
        static::$input = [];
    }

    public static function convert(DataSet $dataSet): array
    {
        static::$input = $dataSet;


        $countDown = 10;
        $stillHavingItemsToProcess = !empty(static::$input);

        $round = 1;

        while ($countDown && $stillHavingItemsToProcess) {
            $stillHavingItemsToProcess = false;
            foreach (static::$input as $oid => $item) {
                if (!isset($item[self::PROCESSED])) {
                    $item = static::processItem($oid, $item);
                    static::$input->update($oid, $item);
                }
                if (!isset($item[self::PROCESSED])) {
                    $stillHavingItemsToProcess = true;
                }
            }
            $countDown--;
            $round++;
        }

        return static::$result;
    }

    protected static function processItem(string $oid, array $item): array
    {
        if (!isset(static::$result[$oid])) {
            static::$result[$oid] = KDMFactory::build($oid, $item, static::$result);
        } else {
            if (Element::STATUS_CLOSED == static::$result[$oid]->getProcessingStatus()) {
                $item[self::PROCESSED] = true;
            } else {
                static::$result[$oid] = KDMFactory::update($oid, $item, static::$result);
            }
        }

        return $item;
    }
}
