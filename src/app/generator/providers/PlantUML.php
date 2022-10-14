<?php

namespace Pyara\app\generator\providers;

use Leuffen\TextTemplate\TextTemplate;
use SysKDB\kdb\repository\DataSet;
use SysKDB\kdm\code\ClassUnit;
use SysKDB\kdm\code\InterfaceUnit;
use SysKDB\kdm\code\KExtends;
use SysKDB\kdm\code\MemberUnit;
use SysKDB\kdm\code\MethodKind;
use SysKDB\kdm\code\MethodUnit;
use SysKDB\kdm\code\StringType;
use SysKDB\kdm\code\IntegerType;
use SysKDB\kdm\code\Kimplements;
use SysKDB\lib\Constants;

class PlantUML implements ProviderInterface
{
    /**
     * @var TextTemplate
     */
    protected $tt;

    /**
     * @var DataSet
     */
    protected $dataSet;

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


    protected function initTemplate($str)
    {
        $this->tt = new TextTemplate($str);

        $this->tt->addFunction(
            "echoBrackets",
            function ($paramArr, $command, $context, $cmdParam, $self) {
                return '{' . ($paramArr["text"] ?? '') . '}';
            }
        );

        $this->tt->addFunction(
            "echoBracketsIfTrue",
            function ($paramArr, $command, $context, $cmdParam, $self) {
                if ($paramArr["compare"]) {
                    return '{' . ($paramArr["text"] ?? '') . '}';
                } else {
                    return '';
                }
            }
        );

        //echoBrackets
    }

    public function generateClassDiagram(DataSet $dataSet): string
    {
        $this->setDataSet($dataSet);

        $response = '';

        $response .= $this->generateInterfaceBlock();
        $response .= $this->generateClassBlock();

        return $response;
    }

    protected function generateInterfaceBlock()
    {
        $templateInterface = <<<EOD
interface {= name }{if extendsFromName } extends {= extendsFromName}{/if}
{= newline}
{for method in methodsList}{= name } : {= method.visibility} {= method.dataType} {= method.name }(){= newline}{/for}

{for implementation in implementations }
{= name } <|.. {= implementation.name}
{/for}
EOD;
        $this->initTemplate($templateInterface);


        $response = '';

        $listInterfaces = $this->dataSet->findByKeyValueAttribute(Constants::INTERNAL_CLASS_NAME, InterfaceUnit::class);

        foreach ($listInterfaces as $interface) {
            $interface['newline'] = "\n";
            $this->processInterfaceRelations($interface);
            $this->processClassMethods($interface);
            // $this->processClassAttributes($class);
            $response .= $this->tt->apply($interface).  "\n";
        }


        return $response;
    }

    protected function processInterfaceRelations(&$interface)
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
    protected function generateClassBlock()
    {
        $templateClass = <<<EOD
{if isAbstract == true}abstract {/if}class {= name } {if extendsFromName }extends {= extendsFromName} {/if}{
{for attribute in attributesList}
    {= attribute.visibility} {= attribute.type} {= attribute.name }
{/for}{= newline}
{for method in methodsList}   {echoBracketsIfTrue text="abstract" compare=method.isAbstract} {= method.visibility} {= method.dataType} {= method.name }(){= newline}{/for}}

EOD;
        $this->initTemplate($templateClass);


        $response = '';

        $listClasses = $this->dataSet->findByKeyValueAttribute(Constants::INTERNAL_CLASS_NAME, ClassUnit::class);

        foreach ($listClasses as $class) {
            $class['newline'] = "\n";
            $this->processClassRelations($class);
            $this->processClassMethods($class);
            $this->processClassAttributes($class);
            $response .= $this->tt->apply($class).  "\n";
        }


        return $response;
    }

    protected function processClassRelations(&$class)
    {
        $extendsFrom = null;
        $extendsFromName = '';
        if (is_object($class['codeRelation'])) {
            foreach ($class['codeRelation'] as $codeRelation) {
                if (KExtends::class === $codeRelation->getInternalClassName()) {
                    if ($class[Constants::OID] === $codeRelation->getFrom()->getOid()) {
                        $extendsFrom = $codeRelation->getTo();
                        $extendsFromName = $codeRelation->getTo()->getName();
                    }
                }
            }
        }
        $class['extendsFrom'] = $extendsFrom;
        $class['extendsFromName'] = $extendsFromName;
    }



    protected function processClassAttributes(&$class)
    {
        $class['attributesList'] = [];
        // Extracting the methods
        foreach ($class['ownedElements'] as $ownedElement) {
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
                        }
                    } elseif (is_object($record['type'])) {
                        $dataType = $record['type']->getInternalClassName();
                    }
                }
                $attribute['type'] = static::DATATYPE_MAP[$dataType] ?? '';

                $class['attributesList'][] = $attribute;
            }
        }
    }

    protected function processClassMethods(&$class)
    {
        $class['methodsList'] = [];
        // Extracting the methods
        foreach ($class['ownedElements'] as $ownedElement) {
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
}
