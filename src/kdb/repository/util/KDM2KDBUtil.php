<?php

namespace SysKDB\kdb\repository\util;

use SysKDB\kdm\core\Element;
use SysKDB\kdm\lib\Constants;
use SysKDB\kdm\lib\Enumeration;
use SysKDB\kdm\lib\ListBase;
use SysKDB\lib\Constants as LibConstants;

class KDM2KDBUtil
{
    protected static $result = [];
    protected static $counter = 0;
    protected static $version = '1';


    /**
     * Convert the KDM representation to KDB objects, in order to
     * store on DB
     *
     * @param Element $element
     * @return array
     */
    public static function convert(Element $element, string $version='1'): array
    {
        static::$version = $version;
        static::$counter = 0;
        static::$result = [];

        static::convertItem($element);

        return static::$result;
    }


    protected static function convertItem(Element $element)
    {
        $currentOid = $element->getOid();
        // echo "\n\n currentOid = $currentOid  / " . static::$counter;
        if (isset(static::$result[$currentOid])) {
            return $currentOid;
        }
        $exported = $element->export();
        if (isset($exported[Constants::OBJ_DATA])) {
            $exportedData = $exported[Constants::OBJ_DATA];
        } else {
            $exportedData = $exported;
        }

        static::$result[$currentOid] = [
            LibConstants::CLASSNAME => get_class($element),
            LibConstants::VERSION =>static::$version
        ];

        foreach ($exportedData as $k => $data) {
            static::$counter++;
            if (is_null($data)) {
                continue;
            }
            if (is_scalar($data)) {
                static::$result[$currentOid][$k] = $data;
            } elseif (is_object($data)) {
                if (is_a($data, ListBase::class)) {
                    $ls = $data->getList();
                    foreach ($ls as $k2 => $item) {
                        $returnedOid = static::convertItem($item);
                        if ($returnedOid) {
                            if (!isset(static::$result[$currentOid][$k])) {
                                static::$result[$currentOid][$k] = [];
                            }
                            static::$result[$currentOid][$k][] = $returnedOid;
                        }
                    }
                } elseif (is_a($data, Element::class)) {
                    $returnedOid = static::convertItem($data);
                    if ($returnedOid) {
                        if (!isset(static::$result[$currentOid][$k])) {
                            static::$result[$currentOid][$k] = [];
                        }
                        static::$result[$currentOid][$k][] = $returnedOid;
                    }
                } elseif (is_a($data, Enumeration::class)) {
                    static::$result[$currentOid][$k] = $data->getOid();
                } else {
                    echo "\n\n SOF ERROR 1 - UNKNOWN  ($k) \n";
                    var_dump($exportedData);
                    var_dump($data);

                    echo "\n\n EOF ERROR 1 - UNKNOWN  ($k) " . get_class($data) . "\t isList=" . is_a($data, ListBase::class) . "\n";
                    print_r(static::$result);
                    exit;
                }
            } else {
                echo "\n\n ERROR 2 - UNKNOWN \n";
                var_dump($data);
                echo "\n\n ERROR 2 - UNKNOWN  ($k) \t isList=" . is_a($data, ListBase::class) . "\n";
                print_r(static::$result);
                exit;
            }
        }
        return $currentOid;
    }

    public static function reset()
    {
        static::$result = [];
    }
}
