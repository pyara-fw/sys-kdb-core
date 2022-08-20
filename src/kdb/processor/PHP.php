<?php

namespace SysKDB\kdb\processor;



class PHP extends ProcessorBase
{
    const STARTED_SCRIPT_PHP = 'STARTED_SCRIPT_PHP';
    const STOPPED_SCRIPT_PHP = 'STOPPED_SCRIPT_PHP';

    const STATE_SCRIPT_PHP_ON = 'STATE_SCRIPT_PHP_ON';
    const STATE_SCRIPT_PHP_OFF = 'STATE_SCRIPT_PHP_OFF';

    const STATE_BUILDING_NAMESPACE = 'STATE_BUILDING_NAMESPACE';
    const STATE_BUILDING_CLASS = 'STATE_BUILDING_CLASS';
    const STATE_EXTENDING_CLASS = 'STATE_EXTENDING_CLASS';
    const STATE_IMPLEMENTS_CLASS = 'STATE_IMPLEMENTS_CLASS';
    const STATE_BODY_CLASS = 'STATE_BODY_CLASS';
    const STATE_USE_TRAIT = 'STATE_USE_TRAIT';
    const STATE_START_TRAIT = 'STATE_START_TRAIT';
    const STATE_START_CONST = 'STATE_START_CONST';
    const STATE_BUILD_CONST = 'STATE_BUILD_CONST';
    const STATE_BUILD_CONST_VALUE = 'STATE_BUILD_CONST_VALUE';
    const STATE_START_CLASS_MEMBER = 'STATE_START_CLASS_MEMBER';
    const STATE_START_CLASS_MEMBER_ATTRIBUTE = 'STATE_START_CLASS_MEMBER_ATTRIBUTE';
    const STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE = 'STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE';
    const STATE_START_CLASS_MEMBER_METHOD = 'STATE_START_CLASS_MEMBER_METHOD';
    const STATE_START_CLASS_MEMBER_METHOD_PARMS = 'STATE_START_CLASS_MEMBER_METHOD_PARMS';
    const STATE_START_CLASS_MEMBER_METHOD_BODY = 'STATE_START_CLASS_MEMBER_METHOD_BODY';
    const STATE_START_FUNCTION = 'STATE_START_FUNCTION';
    const STATE_START_FUNCTION_BODY = 'STATE_START_FUNCTION_BODY';
    const STATE_START_FUNCTION_PARMS = 'STATE_START_FUNCTION_PARMS';
    const STATE_SCRIPT_PHP_ROOT = 'STATE_SCRIPT_PHP_ROOT';
    const STATE_FUNCTION_DEFINE_RETURN_TYPE = 'STATE_FUNCTION_DEFINE_RETURN_TYPE';
    const STATE_CLASS_MEMBER_METHOD_PARMS_DEFINE_RETURN_TYPE = 'STATE_CLASS_MEMBER_METHOD_PARMS_DEFINE_RETURN_TYPE';
    const STATE_START_INCLUDE = 'STATE_START_INCLUDE';
    const STATE_PATH_INCLUDE = 'STATE_PATH_INCLUDE';
    const STATE_CALL_STATIC = 'STATE_CALL_STATIC';
    const STATE_CALL = 'STATE_CALL';

    

    public function __construct()
    {
        $this->addGeneralTransitions();
        $this->addNamespaceTransitions();
        $this->addClassTransitions();
        $this->addClassHasTraitTransitions();
        $this->addClassHasConstantTransitions();
        $this->addClassMemberVisibilityTransitions();
        $this->addClassMemberMethodTransitions();
        $this->addClassMemberMethodBodyTransitions();

        $this->addClassMemberAttributeTransitions();

        $this->addFunctionsTransitions();
        $this->addIncludeTransitions();

        

    }

    protected function addGeneralTransitions() {

        $actionStarting = new Action( function ($parms=[]) { 
            // echo "\nStarting script\n";
            $parms[KDB_FSM]->activateState(self::STATE_SCRIPT_PHP_ON);
            $parms[KDB_FSM]->activateState(self::STATE_SCRIPT_PHP_ROOT);
         } );
        $actionStopping = new Action( function ($parms=[]) { 
            // echo "\nStopping script\n"; 
            // print_r($parms);
            // echo "\n\n";
            $parms[KDB_FSM]->activateState(self::STATE_SCRIPT_PHP_OFF);
        } );


        $actionDecrementBrackets = new Action(
            function ($parms) {
                $counter = $parms[KDB_FSM]->getVar('brackets',0);
                $counter--;
                $parms[KDB_FSM]->setVar('brackets',$counter);
                $currentLine = $parms[KDB_FSM]->getVar('current_line');
                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $metaClass = $this->hashGet('declared_classes',$currentClassName);

//PHP::STATE_BODY_CLASS

                if ($counter == 0 && $parms[KDB_FSM]->isActiveState(PHP::STATE_BODY_CLASS)) {
                    
                    $metaClass['ending_line'] = $currentLine ;
                    $this->hashSet('declared_classes',$currentClassName, $metaClass);

                    $parms[KDB_FSM]->setVar('interfaces',[]);
                    $parms[KDB_FSM]->setVar('extension_class_name',null);

                    $parms[KDB_FSM]->activateState(self::STATE_SCRIPT_PHP_ROOT);
                    $parms[KDB_FSM]->deactivateState(PHP::STATE_BODY_CLASS);
                    // $parms[KDB_FSM]->setVar('current_class_name',null);
                } elseif ($counter === $parms[KDB_FSM]->getVar('brackets_method_starting_point',-1)) {

                    $methodName = $parms[KDB_FSM]->getVar('current_member_name');
                    $metaClass['methods'][$methodName]['ending_line'] =  $currentLine;
                    $this->hashSet('declared_classes',$currentClassName, $metaClass);

                    $parms[KDB_FSM]->deactivateState(PHP::STATE_START_CLASS_MEMBER_METHOD_BODY);
                    $parms[KDB_FSM]->activateState(PHP::STATE_BODY_CLASS);

                    $parms[KDB_FSM]->setVar('current_member_scope',null);                
                    $parms[KDB_FSM]->setVar('current_member_name',null);
                    $parms[KDB_FSM]->setVar('brackets_method_starting_point',null);
                } elseif ($counter === $parms[KDB_FSM]->getVar('brackets_function_starting_point',-1)) {                    

                    $functionName = $parms[KDB_FSM]->getVar('current_function_name');
                    $metaFunction = $parms[KDB_FSM]->hashGet('declared_functions',$functionName);
                    $metaFunction['ending_line'] = $currentLine;
                    $parms[KDB_FSM]->hashSet('declared_functions',$functionName, $metaFunction);

                    $parms[KDB_FSM]->setVar('brackets_function_starting_point',null);
                    $parms[KDB_FSM]->deactivateState(PHP::STATE_START_FUNCTION_BODY);
                    $parms[KDB_FSM]->activateState(self::STATE_SCRIPT_PHP_ROOT);
                }
            }
        );


        $actionIncrementBrackets = new Action(
            function ($parms) {
                $counter = $parms[KDB_FSM]->getVar('brackets',0);
                $counter++;
                $parms[KDB_FSM]->setVar('brackets',$counter);
            }
        );


        $actionCheckNewLines = new Action(
            function ($parms) {
                $text = $parms[KDB_TOKEN][1];
                if (strlen($text) == 12 && substr($text,0,3) === "'[<" && substr($text,-3) === ">]'") { 
                    $currentLine = intval(substr($text,3,-3));
                    $parms[KDB_FSM]->setVar('current_line',$currentLine);
                }
            }
        );


        $this->add(self::INITIAL_STATE, self::STARTED_SCRIPT_PHP, new ConditionTokenId(T_OPEN_TAG), $actionStarting);
        $this->add(self::INITIAL_STATE, self::STOPPED_SCRIPT_PHP, new ConditionTokenId(T_CLOSE_TAG), $actionStopping);

        $this->add(self::INITIAL_STATE, null,  new ConditionTokenLiteral('}'), $actionDecrementBrackets);
        $this->add(self::INITIAL_STATE, null,  new ConditionTokenLiteral('{'), $actionIncrementBrackets);
        $this->add(self::INITIAL_STATE, null,  new ConditionTokenId(T_CONSTANT_ENCAPSED_STRING), $actionCheckNewLines);

    }

    protected function addNamespaceTransitions() {

        $actionBuildingNamespace = new Action(
            function ($parms) {
                $currentNamespace = $parms[KDB_FSM]->getVar('current_namespace','');
                $currentNamespace .= $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_namespace',$currentNamespace);
            }
        );

        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_NAMESPACE, new ConditionTokenId(T_NAMESPACE));
        $this->add(self::STATE_BUILDING_NAMESPACE, self::STATE_BUILDING_NAMESPACE, new ConditionTokenId(T_STRING), $actionBuildingNamespace);
        $this->add(self::STATE_BUILDING_NAMESPACE, self::STATE_BUILDING_NAMESPACE, new ConditionTokenId(T_NS_SEPARATOR), $actionBuildingNamespace);
        $this->add(self::STATE_BUILDING_NAMESPACE, null, new ConditionTokenLiteral(';'));
    }

    protected function addClassTransitions() 
    {


        $actionRecordClassType = new Action(
            function ($parms) {
                $classType = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_class_type',$classType);
                $parms[KDB_FSM]->setVar('current_class_line',$parms[KDB_FSM]->getVar('current_line',1));
            }
        );


        $actionRecordAbstract = new Action(
            function ($parms) {
                $parms[KDB_FSM]->setVar('current_class_abstract',true);
                $parms[KDB_FSM]->setVar('current_class_line',$parms[KDB_FSM]->getVar('current_line',1));
            }
        );


        $actionSaveCurrentClassName = new Action(
            function ($parms) {
                // $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $currentClassName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_class_name',$currentClassName);
                // echo "\n Found the class $currentClassName \n";
                $parms[KDB_FSM]->deactivateState(self::STATE_SCRIPT_PHP_ROOT);                
            }
        );

        $actionSaveExtensionName = new Action(
            function ($parms) {
                // $extensionClassName = $parms[KDB_FSM]->getVar('extension_class_name','');
                // $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $extensionClassName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('extension_class_name',$extensionClassName);
                // echo "\n class '$currentClassName' extends from '$extensionClassName' \n";
            }
        );

        $actionSaveInterfaceName = new Action(
            function ($parms) {
                $interfaces = $parms[KDB_FSM]->getVar('interfaces',[]);
                $interfaceName = $parms[KDB_TOKEN][1];
                $interfaces[] = $interfaceName;

                $parms[KDB_FSM]->setVar('interfaces',$interfaces);
            }
        );

        $actionRegisterStartingClass = new Action(
            function ($parms) {
                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');

                $this->pushArray('declared_class_names', $currentClassName);

                $metaClass = [
                    'name' => $currentClassName,
                    'extends' => $parms[KDB_FSM]->getVar('extension_class_name',''),
                    'implements' => $parms[KDB_FSM]->getVar('interfaces',[]),
                    'type' => $parms[KDB_FSM]->getVar('current_class_type','class'),
                    'starting_line' => $parms[KDB_FSM]->getVar('current_line'),
                    'namespace' => $parms[KDB_FSM]->getVar('current_namespace','')
                ];

                if ($parms[KDB_FSM]->getVar('current_class_abstract')) {
                    $metaClass['is_abstract'] = true;
                    $parms[KDB_FSM]->setVar('current_class_abstract',null);
                }
                
                

                $this->hashSet('declared_classes',$currentClassName, $metaClass);

                

            
            }
        );        

        $this->add(self::INITIAL_STATE, self::INITIAL_STATE, new ConditionTokenId(T_ABSTRACT), $actionRecordAbstract);
        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_CLASS), $actionRecordClassType);
        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_INTERFACE), $actionRecordClassType);
        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_TRAIT), $actionRecordClassType);

        $this->add(self::STATE_BUILDING_CLASS, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_STRING), $actionSaveCurrentClassName);
        
        // $this->add(self::STATE_BUILDING_CLASS, null, new ConditionTokenLiteral('{'), $actionRegisterStartingClass);
        $this->add(self::STATE_BUILDING_CLASS, self::STATE_BODY_CLASS, new ConditionTokenLiteral('{'), $actionRegisterStartingClass);

        $this->add(self::STATE_BUILDING_CLASS, self::STATE_EXTENDING_CLASS, new ConditionTokenId(T_EXTENDS));
        $this->add(self::STATE_EXTENDING_CLASS, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_STRING), $actionSaveExtensionName);
        
        $this->add(self::STATE_BUILDING_CLASS, self::STATE_IMPLEMENTS_CLASS, new ConditionTokenId(T_IMPLEMENTS));

        $this->add(self::STATE_IMPLEMENTS_CLASS, self::STATE_IMPLEMENTS_CLASS, new ConditionTokenId(T_STRING), $actionSaveInterfaceName);
        $this->add(self::STATE_IMPLEMENTS_CLASS, self::STATE_BODY_CLASS, new ConditionTokenLiteral('{'), $actionRegisterStartingClass);

    
    }

    protected function addClassHasTraitTransitions() {


        $actionRecordtrait = new Action(
            function ($parms) {
                $traitName = $parms[KDB_TOKEN][1];

                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $metaClass = $parms[KDB_FSM]->hashGet('declared_classes',$currentClassName);
                if (!isset($metaClass['traits'])) {
                    $metaClass['traits'] = [];
                }
                $metaClass['traits'][] = $traitName;
                $this->hashSet('declared_classes',$currentClassName, $metaClass);
            }
        );

        $this->add( self::STATE_BODY_CLASS, self::STATE_START_TRAIT , new ConditionTokenId(T_USE));
        $this->add( self::STATE_START_TRAIT, self::STATE_USE_TRAIT , new ConditionTokenId(T_STRING), $actionRecordtrait);
        $this->add( self::STATE_USE_TRAIT ,self::STATE_BODY_CLASS, new ConditionTokenLiteral(';'));

    }

    protected function addClassHasConstantTransitions() {



        $actionRecordConstName = new Action(
            function ($parms) {
                $constName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('const_name',$constName);
            }
        );

        $actionRecordConstValue = new Action(
            function ($parms) {
                $constValue = $parms[KDB_TOKEN][1];
                
                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $metaClass = $parms[KDB_FSM]->hashGet('declared_classes',$currentClassName);
                if (!isset($metaClass['const'])) {
                    $metaClass['const'] = [];
                }
                $constName = $parms[KDB_FSM]->getVar('const_name');
                if (!isset($metaClass['const'][$constName])) {
                    $metaClass['const'][$constName] = [];
                }
                $metaClass['const'][$constName]['value'] = $constValue ;
                $this->hashSet('declared_classes',$currentClassName, $metaClass);
                $parms[KDB_FSM]->setVar('const_name',null);
            }
        );



        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CONST , new ConditionTokenId(T_CONST));
        $this->add( self::STATE_START_CONST, self::STATE_BUILD_CONST , new ConditionTokenId(T_STRING), $actionRecordConstName);
        $this->add( self::STATE_BUILD_CONST ,self::STATE_BUILD_CONST_VALUE, new ConditionTokenLiteral('='));
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_STRING), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_DNUMBER), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_LNUMBER), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_CONSTANT_ENCAPSED_STRING), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE ,self::STATE_BODY_CLASS, new ConditionTokenLiteral(';'));

    }

    protected function addClassMemberVisibilityTransitions() {



        $actionStartClassMember = new Action(
            function ($parms) {
                $scope = 'public';
                switch ($parms[KDB_TOKEN][0]) {
                    case T_PUBLIC:
                        $scope = 'public';
                        break;
                    case T_PROTECTED:
                        $scope = 'protected';
                        break;
                    case T_PRIVATE:
                        $scope = 'private';
                        break;
                }

                $parms[KDB_FSM]->setVar('current_member_scope',$scope);
            }
        );


        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CLASS_MEMBER , new ConditionTokenId(T_PROTECTED), $actionStartClassMember);
        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CLASS_MEMBER , new ConditionTokenId(T_PUBLIC), $actionStartClassMember);
        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CLASS_MEMBER , new ConditionTokenId(T_PRIVATE), $actionStartClassMember);

    }

    protected function addClassMemberMethodTransitions() {



        $actionStartClassMemberMethod = new Action(
            function ($parms) {
                $methodName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_member_name',$methodName);
                $parms[KDB_FSM]->setVar('class_member_starting_line', $parms[KDB_FSM]->getVar('current_line'));
            }
        );

        $actionSaveClassMemberMethod = new Action(
            function ($parms) {
                
                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $metaClass = $parms[KDB_FSM]->hashGet('declared_classes',$currentClassName);
                $memberScope = $parms[KDB_FSM]->getVar('current_member_scope','public');
                $methodName = $parms[KDB_FSM]->getVar('current_member_name');

                if (!isset($metaClass['methods'])) {
                    $metaClass['methods'] = [];
                }
                if (!isset($metaClass['methods'][$methodName])) {
                    $metaClass['methods'][$methodName] = [];
                }

                $metaClass['methods'][$methodName]['scope'] = $memberScope;                
                $metaClass['methods'][$methodName]['starting_line'] =  $parms[KDB_FSM]->getVar('class_member_starting_line',1);
                $metaClass['methods'][$methodName]['ending_line'] =  $parms[KDB_FSM]->getVar('current_line');                

                $this->hashSet('declared_classes',$currentClassName, $metaClass);
                // $parms[KDB_FSM]->setVar('current_member_scope',null);                
                // $parms[KDB_FSM]->setVar('current_member_name',null);
                $parms[KDB_FSM]->setVar('brackets_method_starting_point',$parms[KDB_FSM]->getVar('brackets'));                
                
            }
        );


        $actionSaveClassMemberMethodReturnType = new Action(
            function ($parms) {

                $returnType = $parms[KDB_TOKEN][1];

                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $metaClass = $parms[KDB_FSM]->hashGet('declared_classes',$currentClassName);

                $methodName = $parms[KDB_FSM]->getVar('current_member_name');
                if (!isset($metaClass['methods'])) {
                    $metaClass['methods'] = [];
                }
                if (!isset($metaClass['methods'][$methodName])) {
                    $metaClass['methods'][$methodName] = [];
                }

                $metaClass['methods'][$methodName]['return_type'] = $returnType;
                
                $this->hashSet('declared_classes',$currentClassName, $metaClass);

            }
        );


        $this->add( self::STATE_BODY_CLASS,self::STATE_START_CLASS_MEMBER_METHOD , new ConditionTokenId(T_FUNCTION));
        $this->add( self::STATE_START_CLASS_MEMBER,self::STATE_START_CLASS_MEMBER_METHOD , new ConditionTokenId(T_FUNCTION));
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD,self::STATE_START_CLASS_MEMBER_METHOD_PARMS , new ConditionTokenId(T_STRING), $actionStartClassMemberMethod);
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_PARMS,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenLiteral('{'), $actionSaveClassMemberMethod);
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_PARMS,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenLiteral(';'), $actionSaveClassMemberMethod);

        $this->add(self::STATE_START_CLASS_MEMBER_METHOD_PARMS, self::STATE_CLASS_MEMBER_METHOD_PARMS_DEFINE_RETURN_TYPE, new ConditionTokenLiteral(':'));
        $this->add(self::STATE_CLASS_MEMBER_METHOD_PARMS_DEFINE_RETURN_TYPE, self::STATE_START_CLASS_MEMBER_METHOD_PARMS, new ConditionTokenId(T_STRING), $actionSaveClassMemberMethodReturnType);    

    }

    protected function addClassMemberMethodBodyTransitions() {

        $actionStorePotentialIdentifier = new Action(
            function ($parms) {
                $identifier = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('identifier',$identifier);

                if ($parms[KDB_FSM]->isActiveState(self::STATE_CALL_STATIC)
                || $parms[KDB_FSM]->isActiveState(self::STATE_CALL) ) {
                    $parms[KDB_FSM]->setVar('called',$identifier);
                }
            }
        );

        $actionRegisterStaticCall = new Action(
            function ($parms) {
                $identifier = $parms[KDB_FSM]->getVar('identifier');
                $caller = $parms[KDB_FSM]->getVar('caller');
                if ($caller) {
                    //
                } elseif ($identifier) {
                    $parms[KDB_FSM]->setVar('caller',$identifier);                    
                    $parms[KDB_FSM]->activateState(self::STATE_CALL_STATIC);
                }
                $parms[KDB_FSM]->setVar('operator','::');
            }
        );

        $actionRegisterCall = new Action(
            function ($parms) {
                $identifier = $parms[KDB_FSM]->getVar('identifier');
                $caller = $parms[KDB_FSM]->getVar('caller');
                if ($caller) {
                    //
                } elseif ($identifier) {
                    $parms[KDB_FSM]->setVar('called',$identifier);                    
                    $parms[KDB_FSM]->activateState(self::STATE_CALL);
                }
                $parms[KDB_FSM]->setVar('operator','->');
            }
        );

        $actionStartParameters = new Action(
            function ($parms) {
                $caller = $parms[KDB_FSM]->getVar('caller');
                $called = $parms[KDB_FSM]->getVar('called');
                $operator = $parms[KDB_FSM]->getVar('operator');
                if ($caller && $called) {
                    $data = [
                        'caller' => $caller,
                        'called' => $called,
                        'operator' => $operator
                    ];

                    $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                    $metaClass = $parms[KDB_FSM]->hashGet('declared_classes',$currentClassName);
    
                    $methodName = $parms[KDB_FSM]->getVar('current_member_name');
    
                    if (!isset($metaClass['methods'][$methodName]['dependencies'])) {
                        $metaClass['methods'][$methodName]['dependencies'] = [];
                    }
                    $metaClass['methods'][$methodName]['dependencies'][] = $data;
                    $parms[KDB_FSM]->setVar('caller', $caller . $operator . $called . '()');                    
                    $parms[KDB_FSM]->activateState(self::STATE_CALL_STATIC);
                }
            }
        );

        $actionSaveAndResetCall = new Action( 
            function($parms) {
                // Save everything

                // Reset
                $parms[KDB_FSM]->setVar('caller',null);
                $parms[KDB_FSM]->setVar('called',null);
                $parms[KDB_FSM]->setVar('identifier',null);
                $parms[KDB_FSM]->deactivateState(self::STATE_CALL_STATIC);
                $parms[KDB_FSM]->deactivateState(self::STATE_CALL);
            }
        ); 

        $actionSaveAndRestartCall = new Action( 
            function($parms) {
                // Save everything

                // Reset
                $parms[KDB_FSM]->setVar('caller',null);
                $parms[KDB_FSM]->setVar('called',null);
                $parms[KDB_FSM]->setVar('identifier',null);
                $parms[KDB_FSM]->deactivateState(self::STATE_CALL_STATIC);
                $parms[KDB_FSM]->deactivateState(self::STATE_CALL);
            }
        ); 


        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_BODY,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenId(T_STRING), $actionStorePotentialIdentifier);        
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_BODY,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenId(T_DOUBLE_COLON), $actionRegisterStaticCall);
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_BODY,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenId(T_OBJECT_OPERATOR), $actionRegisterCall);
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_BODY,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenLiteral('('), $actionStartParameters);
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_BODY,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenLiteral(';'), $actionSaveAndResetCall);
    }

    protected function addClassMemberAttributeTransitions() {


        $actionStartClassMemberAttribute = new Action(
            function ($parms) {
                $varName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_member_name',$varName);
            }
        );

        $actionStartClassMemberAttributeType = new Action(
            function ($parms) {
                $varType = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_member_type',$varType);
            }
        );

        $actionStartClassMemberAttributeValue = new Action(
            function ($parms) {
                // append
                $varValue = $parms[KDB_FSM]->getVar('current_member_value','');
                $varValue .= $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_member_value',$varValue);
            }
        );

        $actionSaveClassMemberAttribute = new Action(
            function ($parms) {
                $varValue = $parms[KDB_FSM]->getVar('current_member_value');

                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $metaClass = $parms[KDB_FSM]->hashGet('declared_classes',$currentClassName);
                if (!isset($metaClass['attributes'])) {
                    $metaClass['attributes'] = [];
                }
                $varScope = $parms[KDB_FSM]->getVar('current_member_scope');
                $varName = $parms[KDB_FSM]->getVar('current_member_name');

                $metaClass['attributes'][$varName] = [
                    'scope' => $varScope,
                    'value' => $varValue
                    ] ;
                

                $this->hashSet('declared_classes',$currentClassName, $metaClass);
                $parms[KDB_FSM]->setVar('current_member_scope',null);                
                $parms[KDB_FSM]->setVar('current_member_name',null);                
                $parms[KDB_FSM]->setVar('current_member_value',null);                
            }
        );


        $this->add( self::STATE_START_CLASS_MEMBER,self::STATE_START_CLASS_MEMBER_ATTRIBUTE , new ConditionTokenId(T_VARIABLE), $actionStartClassMemberAttribute);
        $this->add( self::STATE_START_CLASS_MEMBER,self::STATE_START_CLASS_MEMBER , new ConditionTokenId(T_STRING), $actionStartClassMemberAttributeType);

        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE ,self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE, new ConditionTokenLiteral('='));
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE, self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE , new ConditionTokenId(T_STRING), $actionStartClassMemberAttributeValue);
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE, self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE , new ConditionTokenId(T_DNUMBER), $actionStartClassMemberAttributeValue);
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE, self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE , new ConditionTokenId(T_LNUMBER), $actionStartClassMemberAttributeValue);
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE, self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE , new ConditionTokenId(T_CONSTANT_ENCAPSED_STRING), $actionStartClassMemberAttributeValue);
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE, self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE , new ConditionTokenId(T_DOUBLE_COLON), $actionStartClassMemberAttributeValue);
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE, self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE , new ConditionTokenId(T_STATIC), $actionStartClassMemberAttributeValue);
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE_VALUE ,self::STATE_BODY_CLASS, new ConditionTokenLiteral(';'), $actionSaveClassMemberAttribute);
        $this->add( self::STATE_START_CLASS_MEMBER_ATTRIBUTE ,self::STATE_BODY_CLASS, new ConditionTokenLiteral(';'), $actionSaveClassMemberAttribute);
        
    }

    protected function addFunctionsTransitions() {



        $actionStartFunction = new Action(
            function ($parms) {
                $functionName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_function_name',$functionName);
                
                $this->pushArray('declared_function_names', $functionName);


                $metaFunction = [
                    'name' => $functionName,
                    'starting_line' => $parms[KDB_FSM]->getVar('current_line')
                ];
                $this->hashSet('declared_functions',$functionName, $metaFunction);

            }
        );


        $actionStartFunctionBody = new Action(
            function ($parms) {
                if (!$parms[KDB_FSM]->getVar('brackets')) {
                    $parms[KDB_FSM]->setVar('brackets',1);
                }
                $parms[KDB_FSM]->setVar('brackets_function_starting_point',$parms[KDB_FSM]->getVar('brackets'));
                $parms[KDB_FSM]->deactivateState(self::STATE_SCRIPT_PHP_ROOT);
            }
        );

        $actionFunctionStoreReturnType = new Action(
            function ($parms) {
                $returnType = $parms[KDB_TOKEN][1];
                $functionName = $parms[KDB_FSM]->getVar('current_function_name');
                $metaFunction = $this->hashGet('declared_functions',$functionName);

                $metaFunction['return_type'] = $returnType;
                $this->hashSet('declared_functions',$functionName, $metaFunction);                

            }
        );


        $this->add(self::STATE_SCRIPT_PHP_ROOT, self::STATE_START_FUNCTION, new ConditionTokenId(T_FUNCTION));
        $this->add(self::STATE_START_FUNCTION, self::STATE_START_FUNCTION_PARMS, new ConditionTokenId(T_STRING), $actionStartFunction);
        
        // $this->add(self::STATE_START_FUNCTION_PARMS, self::STATE_START_FUNCTION_PARMS, new ConditionTokenLiteral('('), $actionStartFunctionBody);
        $this->add(self::STATE_START_FUNCTION_PARMS, self::STATE_START_FUNCTION_BODY, new ConditionTokenLiteral('{'), $actionStartFunctionBody);

        $this->add(self::STATE_START_FUNCTION_PARMS, self::STATE_FUNCTION_DEFINE_RETURN_TYPE, new ConditionTokenLiteral(':'));
        $this->add(self::STATE_FUNCTION_DEFINE_RETURN_TYPE, self::STATE_START_FUNCTION_PARMS, new ConditionTokenId(T_STRING), $actionFunctionStoreReturnType);

    }

    protected function addIncludeTransitions() {



        $actionSaveIncludeType = new Action(
            function ($parms) {
                $includeType = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_include_type',$includeType);
                $parms[KDB_FSM]->setVar('current_include_path',null);
                $parms[KDB_FSM]->setVar('previous_piece',null);
            }
        );

        $actionSaveIncludePathPiece = new Action(
            function ($parms) {

                if (is_array($parms[KDB_TOKEN])) {
                    $piece = $parms[KDB_TOKEN][1];
                } else {
                    $piece = $parms[KDB_TOKEN];
                }
                
                $previousPiece = $parms[KDB_FSM]->getVar('previous_piece','');
                if ($previousPiece != $piece) {
                    $currentPath = $parms[KDB_FSM]->getVar('current_include_path','');
                    $currentPath .= $piece;
                    $parms[KDB_FSM]->setVar('current_include_path',$currentPath);
                }
                $parms[KDB_FSM]->setVar('previous_piece',$piece);                
            }
        );

        $actionSaveInclude = new Action(
            function ($parms) {
                $includeType = $parms[KDB_FSM]->getVar('current_include_type');

                $currentPath = $parms[KDB_FSM]->getVar('current_include_path','');

                $metaInclude = [
                    'type' => $includeType,
                    'path' => $currentPath
                ];

                $parms[KDB_FSM]->pushArray('includes', $metaInclude);
                $parms[KDB_FSM]->setVar('current_include_path',null);
                $parms[KDB_FSM]->setVar('current_include_type',null);
                $parms[KDB_FSM]->setVar('previous_piece',null);
            }
        );



        $this->add(self::STATE_SCRIPT_PHP_ROOT, self::STATE_START_INCLUDE, new ConditionTokenId(T_INCLUDE), $actionSaveIncludeType);
        $this->add(self::STATE_SCRIPT_PHP_ROOT, self::STATE_START_INCLUDE, new ConditionTokenId(T_INCLUDE_ONCE), $actionSaveIncludeType);
        $this->add(self::STATE_SCRIPT_PHP_ROOT, self::STATE_START_INCLUDE, new ConditionTokenId(T_REQUIRE), $actionSaveIncludeType);
        $this->add(self::STATE_SCRIPT_PHP_ROOT, self::STATE_START_INCLUDE, new ConditionTokenId(T_REQUIRE_ONCE), $actionSaveIncludeType);

        $this->add(self::STATE_START_INCLUDE, self::STATE_PATH_INCLUDE, new ConditionTokenId(T_DIR), $actionSaveIncludePathPiece);
        $this->add(self::STATE_START_INCLUDE, self::STATE_PATH_INCLUDE, new ConditionTokenId(T_CONSTANT_ENCAPSED_STRING), $actionSaveIncludePathPiece);

        $this->add(self::STATE_PATH_INCLUDE, self::STATE_PATH_INCLUDE, new ConditionTokenId(T_DIR), $actionSaveIncludePathPiece);
        $this->add(self::STATE_PATH_INCLUDE, self::STATE_PATH_INCLUDE, new ConditionTokenId(T_CONSTANT_ENCAPSED_STRING), $actionSaveIncludePathPiece);
        $this->add(self::STATE_PATH_INCLUDE, self::STATE_PATH_INCLUDE, new ConditionTokenId(T_WHITESPACE), $actionSaveIncludePathPiece);
        $this->add(self::STATE_PATH_INCLUDE, self::STATE_PATH_INCLUDE, new ConditionTokenLiteral('.'), $actionSaveIncludePathPiece);

        $this->add(self::STATE_PATH_INCLUDE, self::STATE_SCRIPT_PHP_ROOT, new ConditionTokenLiteral(';'), $actionSaveInclude);

    }

    public function parse($contents) {

        $contents = $this->assignLineNumber($contents);

        $tokens = token_get_all($contents);

        $auxTokens = $tokens;
        foreach ($auxTokens as $k=>$token) {
            if (is_array($token)) {
                $auxTokens[$k][-1] = token_name($token[0]);
            }
        }
        // file_put_contents('tks.txt', print_r($auxTokens, true));
        

        return $tokens;
    }

    public function assignLineNumber($contents) {

        $lines = explode("\n",$contents);
        $l = 0;
        $numberingActive = false;
        for ($i=0;$i<count($lines);$i++) {
            $l++;
            $currLine = $lines[$i];
            if ($numberingActive == false) {
                $pos = strpos($currLine, '<'."?php");
                if ($pos !== false) {
                    $numberingActive = true;
                    $lines[$i] = substr($currLine,0,$pos+5). sprintf("\t'[<%06d>]'\t",$l).substr($currLine,$pos+5);        
                    continue;
                }
                continue;
            } elseif (strpos($currLine, '?'.">") !== false) {
                $numberingActive = false;
            }            
            $lines[$i] = sprintf("\t'[<%06d>]'\t",$l).$currLine;            
        }
        $contents = implode("\n",$lines);
    

        return $contents;
    }

}



