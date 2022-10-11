<?php

namespace Pyara\cli;

use Minicli\App as MiniCliApp;
use Minicli\Command\CommandCall;
use SysKDB\kdb\processor\KDMBuilder;
use SysKDB\kdb\processor\PHP;

/**
 * @author Eduardo Luz <eduardo@eduardo-luz.com>
 * @package Pyara
 */
class App extends MiniCliApp
{
    public const VERSION = "0.0.1";

    public const PARM_PATH = 'path';

    protected $fileProcessor;

    /**
     * @var CommandCall
     */
    protected $input;


    /**
     * @return CommandCall
     */
    public function getInput()
    {
        return $this->input;
    }


    /**
     *
     *
     * @return FileProcessor
     */
    public function getFileProcessor()
    {
        if (!$this->fileProcessor) {
            $this->fileProcessor = new FileProcessor(
                $this,
                $this->getInput()->getParam(static::PARM_PATH)
            );
        }
        return $this->fileProcessor;
    }


    /**
     * Constructor
     *
     * @param array $config
     * @param string $signature
     */
    public function __construct(array $config = [], string $signature = './minicli help')
    {
        parent::__construct($config, $this->buildSignature());

        $this->init();
    }

    /**
     * @return string
     */
    protected function buildSignature(): string
    {
        $signature = <<<EOD
        8888888b.                                    
        888   Y88b                                   
        888    888                                   
        888   d88P 888  888  8888b.  888d888 8888b.  
        8888888P"  888  888     "88b 888P"      "88b 
        888        888  888 .d888888 888    .d888888 
        888        Y88b 888 888  888 888    888  888 
        888         "Y88888 "Y888888 888    "Y888888 
                        888                          
                    Y8b d88P                          
                    "Y88P" 
        \e[0;32m Ver. VERSION
                            
        EOD;

        return str_replace('VERSION', static::VERSION, $signature);
    }


    /**
     * Initializes the commands
     *
     * @return void
     */
    protected function init()
    {
        $app = $this;

        $this->registerCommand('process-files', function (CommandCall $input) use ($app) {
            $app->processFiles($input);
        });


        $this->registerCommand('test', function (CommandCall $input) {
            $s = '<'. '?php
                class MyClass {
                    var $myVar;
                }
            ';
            $tokens = token_get_all($s);
            $result = [];
            foreach ($tokens as $token) {
                if (is_array($token)) {
                    $token['name'] = token_name($token[0]);
                }
                $result[] = $token;
            }
            print_r($result);
        });
    }



    /**
     *
     * @param CommandCall $input
     * @return void
     */
    public function processFiles(CommandCall $input)
    {
        if (!$input->hasParam(static::PARM_PATH)) {
            throw new \Exception("Param '".static::PARM_PATH."' is required");
        }

        $self = $this;
        $this->input = $input;
        $this->getFileProcessor()->run(function ($list) use ($self) {
            foreach ($list as $filePath) {
                $self->parseFile($filePath);
            }
        });
    }

    /**
     *
     * @param string $filePath
     * @return void
     */
    public function parseFile($filePath)
    {
        $this->getPrinter()->info("Processing file $filePath ");
        $contents = file_get_contents($filePath);
        $processor = new PHP();
        $tokens = $processor->parse($contents);
        $processor->processTokens($tokens);

        $listClasses = $processor->getArrayDeclaredClassNames();

        foreach ($listClasses as $className) {
            $arr = $processor->getAssocClass($className);
            KDMBuilder::buildClass($arr);
            exit;
        }
    }
}
