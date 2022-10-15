<?php

namespace Pyara\app\generator\providers;

use Leuffen\TextTemplate\TextTemplate;
use Pyara\app\generator\PreProcessor;
use SysKDB\kdb\repository\DataSet;
use SysKDB\kdm\code\ClassUnit;
use SysKDB\kdm\code\InterfaceUnit;
use SysKDB\lib\Constants;

class PlantUML
{
    /**
     * @var TextTemplate
     */
    protected $tt;

    protected $preProcessor;


    /**
     * @param string|null $tpl
     * @return TextTemplate
     */
    public function getTemplateProcessor($tpl = null): TextTemplate
    {
        if (!$this->tt || $tpl) {
            $this->initTemplate($tpl);
        }
        return $this->tt;
    }

    public function getPreProcessor()
    {
        if (!$this->preProcessor) {
            $this->preProcessor = new PreProcessor();
        }
        return $this->preProcessor;
    }


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
    }

    public function generateClassDiagram(DataSet $dataSet): string
    {
        $this->getPreProcessor()->setDataSet($dataSet);

        $response = '';

        $response .= $this->generateInterfaceBlock();
        $response .= $this->generateClassBlock();

        return $response;
    }

    protected function generateInterfaceBlock()
    {
        $templateInterface = <<<EOD
interface {= name }{if extendsFromName } extends {= extendsFromName}{/if}

{for method in methodsList}{= name } : {= method.visibility} {= method.dataType} {= method.name }()
{/for}

{for implementation in implementations }
{= name } <|.. {= implementation.name}
{/for}
EOD;
        $this->getTemplateProcessor($templateInterface);

        $response = '';

        $listInterfaces = $this->getPreProcessor()->getDataSet()->findByKeyValueAttribute(Constants::INTERNAL_CLASS_NAME, InterfaceUnit::class);

        foreach ($listInterfaces as $interface) {
            $this->getPreProcessor()->processInterface($interface);
            $response .= $this->getTemplateProcessor()->apply($interface).  "\n";
        }


        return $response;
    }

    protected function generateClassBlock()
    {
        $templateClass = <<<EOD
{if isAbstract == true}abstract {/if}class {= name } {if extendsFromName }extends {= extendsFromName} {/if}{
{for attribute in attributesList}
    {= attribute.visibility} {= attribute.type} {= attribute.name }
{/for}
{for method in methodsList}   {echoBracketsIfTrue text="abstract" compare=method.isAbstract} {= method.visibility} {= method.dataType} {= method.name }()
{/for}}

{for association in associations} 
{= association.origin} -- {if association.destinationSideLabel}"{= association.destinationSideLabel}"{/if} {= association.destination }
{/for}
EOD;
        $this->getTemplateProcessor($templateClass);
        // $this->initTemplate($templateClass);


        $response = '';

        $listClasses = $this->getPreProcessor()->getDataSet()->findByKeyValueAttribute(Constants::INTERNAL_CLASS_NAME, ClassUnit::class);

        foreach ($listClasses as $class) {
            $this->getPreProcessor()->processClass($class);
            $response .= $this->getTemplateProcessor()->apply($class).  "\n";
        }


        return $response;
    }
}
