<?php

namespace SysKDB\kdb\repository\util;

use SysKDB\kdb\repository\DataSet;
use SysKDB\kdm\core\Element;
use SysKDB\lib\Constants;

class KDMFactory
{
    public static function build(string $oid, array &$record, array $context)
    {
        $record[KDB2KDMUtil::PROCESSING] = true;

        if ($record[Constants::CLASSNAME]) {
            $className = $record[Constants::CLASSNAME];
        } else {
            $className = substr($oid, 0, strpos($oid, ':')); // use getInternalClassName
        }

        $obj = new $className();
        $obj->setProcessingStatus(Element::STATUS_OPEN);

        $isPending = false;

        $obj->import($record, function (&$element) use ($record, $context, $oid, &$isPending) {
            $referencedMap = $element->getReferencedAttributesMap();
            foreach ($referencedMap as $field => $caller) {
                if (isset($record[$field])) {
                    if (is_scalar($record[$field])) {
                        $targetOidToFind = $record[$field];
                        if (!isset($context[$targetOidToFind])) {
                            $isPending = true;
                            break;
                        }
                        if (!$isPending) {
                            $obj = $context[$targetOidToFind];
                            $element->$caller($obj);
                        }
                    } else {
                        foreach ($record[$field] as $targetOidToFind) {
                            if (!isset($context[$targetOidToFind])) {
                                $isPending = true;
                                break;
                            }
                        }

                        if (!$isPending) {
                            foreach ($record[$field] as $targetOidToFind) {
                                $obj = $context[$targetOidToFind];
                                $element->$caller($obj);
                            }
                        }
                    }
                }
            }
        });

        if (!$isPending) {
            $obj->setProcessingStatus(Element::STATUS_CLOSED);
            $record[KDB2KDMUtil::PROCESSED] = true;
        }

        return $obj;
    }


    public static function update(string $oid, array &$record, array $context)
    {
        $isPending = false;
        $obj = $context[$oid];

        $obj->apply(function (&$element) use ($record, $context, $oid, &$isPending) {
            $referencedMap = $element->getReferencedAttributesMap();
            foreach ($referencedMap as $field => $caller) {
                if (isset($record[$field])) {
                    if (is_scalar($record[$field])) {
                        $refOid = $record[$field];
                        if (isset($context[$refOid])) {
                            $element->$caller($context[$refOid]);
                        } else {
                            $isPending = true;
                        }
                    } elseif (is_array($record[$field])) {
                        $ls = $record[$field];
                        foreach ($ls as $refOid) {
                            if (isset($context[$refOid])) {
                                $obj = $context[$refOid];
                                $element->$caller($obj);
                            } else {
                                $isPending = true;
                            }
                        }
                    }
                }
            }
        });

        if (!$isPending) {
            $obj->setProcessingStatus(Element::STATUS_CLOSED);
            $record[KDB2KDMUtil::PROCESSED] = true;
        }

        return $obj;
    }
}
