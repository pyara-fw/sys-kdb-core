<?php

namespace Pyara\app\generator;

use SysKDB\kdb\repository\DataSet;
use SysKDB\kdm\code\ClassUnit;
use SysKDB\kdm\code\IntegerType;
use SysKDB\kdm\code\InterfaceUnit;
use SysKDB\kdm\code\KExtends;
use SysKDB\kdm\code\Kimplements;
use SysKDB\kdm\code\MemberUnit;
use SysKDB\kdm\code\MethodKind;
use SysKDB\kdm\code\MethodUnit;
use SysKDB\kdm\code\StringType;
use SysKDB\lib\Constants;

class PreProcessor
{
    public const VISIBILITY_MAP = [
        'public' => '+',
        'protected' => '#',
        'private' => '-',
        'SysKDB\kdm\code\ExportKind::public' => '+',
        'SysKDB\kdm\code\ExportKind::protected' => '#',
        'SysKDB\kdm\code\ExportKind::private' => '-',
    ];

    public const DATATYPE_MAP = [
        StringType::class => 'String',
        IntegerType::class => 'Integer',
    ];

    /**
     * @var DataSet
     */
    protected $dataSet;


    /**
     * Get the value of dataSet
     *
     * @return  DataSet
     */
    public function getDataSet()
    {
        return $this->dataSet;
    }

    /**
     * Set the value of dataSet
     *
     * @param  DataSet  $dataSet
     *
     * @return  self
     */
    public function setDataSet(DataSet $dataSet)
    {
        $this->dataSet = $dataSet;

        return $this;
    }


    public function processClass(&$class)
    {
        $this->processClassRelations($class);
        $this->processClassMethods($class);
        $this->processClassAttributes($class);
    }


    public function processInterface(&$interface)
    {
        $this->processInterfaceRelations($interface);
        $this->processClassMethods($interface);
    }


    public function processClassMethods(&$class)
    {
        $class['methodsList'] = [];

        $ownedElements = $class['ownedElements'] ?? [];
        // Extracting the methods
        foreach ($ownedElements as $ownedElement) {
            $record = $ownedElement->exportVars();

            if ($record[Constants::INTERNAL_CLASS_NAME] === MethodUnit::class) {
                $method = [
                    'name' => $record['name'],
                    'exportKind' => strval($record['exportKind']),
                    'visibility' => static::VISIBILITY_MAP[strval($record['exportKind'])] ?? '',
                    'isAbstract' => false
                ];
                $dataType = '';
                if (is_scalar($record['dataType'])) {
                    $dataType = strval($record['dataType']);
                } else {
                    if (is_array($record['dataType'])) {
                        $item = reset($record['dataType']);
                        $dsType = $this->dataSet->findByKeyValueAttribute(Constants::OID, $item);
                        $itemDataType = $dsType->get(0);
                        if ($itemDataType) {
                            $dataType = $itemDataType[Constants::INTERNAL_CLASS_NAME];
                        }
                    } elseif (is_object($record['dataType'])) {
                        $dataType = $record['dataType']->getInternalClassName();
                    }
                }
                $method['dataType'] = static::DATATYPE_MAP[$dataType] ?? '';

                if ($record['kind']) {
                    if (MethodKind::compare($record['kind'], MethodKind::ABSTRACT)) {
                        $method['isAbstract'] = true;
                    }
                }

                $class['methodsList'][] = $method;
            }
        }
    }

    public function processInterfaceRelations(&$interface)
    {
        $extendsFrom = null;
        $extendsFromName = '';
        $interface['implementations'] = [];
        if (is_object($interface['codeRelation'])) {
            foreach ($interface['codeRelation'] as $codeRelation) {
                if (KExtends::class === $codeRelation->getInternalClassName()) {
                    if ($interface[Constants::OID] === $codeRelation->getFrom()->getOid()) {
                        $extendsFrom = $codeRelation->getTo();
                        $extendsFromName = $codeRelation->getTo()->getName();
                    }
                } elseif (Kimplements::class ===$codeRelation->getInternalClassName()) {
                    if ($interface[Constants::OID] === $codeRelation->getTo()->getOid()) {
                        $interface['implementations'][] = [
                            'class' => $codeRelation->getFrom(),
                            'name' => $codeRelation->getFrom()->getName(),
                        ];
                    }
                }
            }
        }
        $interface['extendsFrom'] = $extendsFrom;
        $interface['extendsFromName'] = $extendsFromName;
    }


    public function processClassRelations(&$class)
    {
        $extendsFrom = null;
        $extendsFromName = '';
        if (is_object($class['codeRelation'])) {
            foreach ($class['codeRelation'] as $codeRelation) {
                if (is_string($codeRelation)) {
                    $result = $this->dataSet->findByKeyValueAttribute(Constants::OID, $codeRelation);
                    $codeRelation = $result->get(0);
                    if ($class[Constants::OID] === $codeRelation['from'][0]) {
                        $codeRelationToOid = $codeRelation['to'][0];
                        $relatedObject = $this->dataSet->findByKeyValueAttribute(Constants::OID, $codeRelationToOid);

                        $this->addAssociation($class, null, $relatedObject->get(0)['name']);
                    }
                } elseif (is_object($codeRelation)) {
                    if (KExtends::class === $codeRelation->getInternalClassName()) {
                        if ($class[Constants::OID] === $codeRelation->getFrom()->getOid()) {
                            $extendsFrom = $codeRelation->getTo();
                            $extendsFromName = $codeRelation->getTo()->getName();
                        }
                    } else {
                        $codeRelationToOid = $codeRelation->getTo()->getOid();
                        if ($class[Constants::OID] === $codeRelationToOid) {
                            $relatedObject = $codeRelation->getFrom();
                            $this->addAssociation($class, null, $relatedObject->getName());
                        }
                    }
                }
            }
        } elseif (is_array($class['codeRelation'])) {
            foreach ($class['codeRelation'] as $codeRelation) {
                if (is_string($codeRelation)) {
                    $result = $this->dataSet->findByKeyValueAttribute(Constants::OID, $codeRelation);
                    $codeRelation = $result->get(0);
                    if (KExtends::class === $codeRelation[Constants::INTERNAL_CLASS_NAME]) {
                        $extendsFrom = $codeRelation['to'][0];
                        $relatedObject = $this->dataSet->findByKeyValueAttribute(Constants::OID, $extendsFrom);
                        $extendsFromName = $relatedObject->get(0)['name'];
                    } else {
                        $codeRelationToOid = $codeRelation['to'][0];
                        if ($class[Constants::OID] === $codeRelationToOid) {
                            $relatedObject = $this->dataSet->findByKeyValueAttribute(Constants::OID, $codeRelationToOid);

                            $this->addAssociation($class, null, $relatedObject->get(0)['name']);
                        }
                    }
                }
            }
        }
        $class['extendsFrom'] = $extendsFrom;
        $class['extendsFromName'] = $extendsFromName;
    }



    public function processClassAttributes(&$class)
    {
        $class['attributesList'] = [];
        $ownedElements = $class['ownedElements'] ?? [];
        // Extracting the methods
        foreach ($ownedElements as $ownedElement) {
            $record = $ownedElement->exportVars();

            if ($record[Constants::INTERNAL_CLASS_NAME] === MemberUnit::class) {
                $attribute = [
                    'name' => $record['name'],
                    'exportKind' => strval($record['export']),
                    'visibility' => static::VISIBILITY_MAP[strval($record['export'])] ?? '',
                ];
                $dataType = '';
                if (is_scalar($record['type'])) {
                    $dataType = strval($record['type']);
                } else {
                    if (is_array($record['type'])) {
                        $item = reset($record['type']);
                        $dsType = $this->dataSet->findByKeyValueAttribute(Constants::OID, $item);
                        $itemDataType = $dsType->get(0);
                        if ($itemDataType) {
                            $dataType = $itemDataType[Constants::INTERNAL_CLASS_NAME];
                            if (in_array($dataType, [ClassUnit::class, InterfaceUnit::class])) {
                                $dataType = $itemDataType['name'];
                                $this->addAssociation($class, $record['name'], $itemDataType['name']);
                            }
                        }
                    } elseif (is_object($record['type'])) {
                        $dataType = $record['type']->getInternalClassName();
                        if (in_array($dataType, [ClassUnit::class, InterfaceUnit::class])) {
                            $dataType = $record['type']->getName();
                            $this->addAssociation($class, $record['name'], $record['type']->getName());
                        }
                    }
                }
                $attribute['type'] = static::DATATYPE_MAP[$dataType] ?? $dataType;

                $class['attributesList'][] = $attribute;
            }
        }
    }


    public function addAssociation(&$class, $attributeName, $attributeTypeName)
    {
        if (!isset($class['associations'])) {
            $class['associations'] = [];
        }
        $association = [
            'origin' => $class['name'],
            'destination' => $attributeTypeName,
            'destinationSideLabel' => $attributeName
        ];
        $class['associations'][] = $association;
    }
}
