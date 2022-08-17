<?php

namespace SysKDB\kdb\processor;


define('KDB_TRANSITION','transition');
define('KDB_FSM','fsm');
define('KDB_PREV_STATE','prev_state');
define('KDB_NEW_STATE','new_state');
define('KDB_TOKEN','token');


class PHP
{
    const INITIAL_STATE = 'INITIAL_STATE';
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

    
    protected $transitions = [];
    protected $activeStates = [];

    protected $vars = [];
    protected $arrays = [];

    protected $hashMap = [];

    public function __construct()
    {

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

        $actionBuildingNamespace = new Action(
            function ($parms) {
                $currentNamespace = $parms[KDB_FSM]->getVar('current_namespace','');
                $currentNamespace .= $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_namespace',$currentNamespace);
            }
        );

        $actionSaveNamespace = new Action(
            function ($parms) {
                $currentNamespace = $parms[KDB_FSM]->getVar('current_namespace','');
                // echo "\n Found the namespace $currentNamespace \n";
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
                ];

                if ($parms[KDB_FSM]->getVar('current_class_abstract')) {
                    $metaClass['is_abstract'] = true;
                    $parms[KDB_FSM]->setVar('current_class_abstract',null);
                }
                
                

                $this->hashSet('declared_classes',$currentClassName, $metaClass);

                

            
            }
        );

        $actionDecrementBrackets = new Action(
            function ($parms) {
                $counter = $parms[KDB_FSM]->getVar('brackets',0);
                $counter--;
                $parms[KDB_FSM]->setVar('brackets',$counter);
                if ($counter == 0) {
                    $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                    // echo "\n Reached final of class $currentClassName ";
                    $parms[KDB_FSM]->setVar('interfaces',[]);
                    $parms[KDB_FSM]->setVar('extension_class_name',null);

                    $parms[KDB_FSM]->activateState(self::STATE_SCRIPT_PHP_ROOT);
                    $parms[KDB_FSM]->deactivateState(PHP::STATE_BODY_CLASS);
                    // $parms[KDB_FSM]->setVar('current_class_name',null);
                } elseif ($counter === $parms[KDB_FSM]->getVar('brackets_method_starting_point',-1)) {

                    // echo "\n closing method " . $parms[KDB_FSM]->getVar('current_member_name')."\n\n";

                    $parms[KDB_FSM]->deactivateState(PHP::STATE_START_CLASS_MEMBER_METHOD_BODY);
                    $parms[KDB_FSM]->activateState(PHP::STATE_BODY_CLASS);

                    $parms[KDB_FSM]->setVar('current_member_scope',null);                
                    $parms[KDB_FSM]->setVar('current_member_name',null);
                    $parms[KDB_FSM]->setVar('brackets_method_starting_point',null);
                } elseif ($counter === $parms[KDB_FSM]->getVar('brackets_function_starting_point',-1)) {
                    $parms[KDB_FSM]->setVar('brackets_function_starting_point',null);
                    $parms[KDB_FSM]->deactivateState(PHP::STATE_START_FUNCTION_BODY);
                    $parms[KDB_FSM]->activateState(self::STATE_SCRIPT_PHP_ROOT);
                    // Save function data
                }

                // echo " $counter ";
            }
        );


        $actionIncrementBrackets = new Action(
            function ($parms) {
                $counter = $parms[KDB_FSM]->getVar('brackets',0);
                $counter++;
                $parms[KDB_FSM]->setVar('brackets',$counter);
            }
        );

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
                $metaClass['const'][$constName] = $constValue ;
                $this->hashSet('declared_classes',$currentClassName, $metaClass);
                $parms[KDB_FSM]->setVar('const_name',null);
            }
        );

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

        $actionStartClassMemberMethod = new Action(
            function ($parms) {
                $methodName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_member_name',$methodName);
            }
        );

        $actionSaveClassMemberMethod = new Action(
            function ($parms) {
                
                $currentClassName = $parms[KDB_FSM]->getVar('current_class_name','');
                $metaClass = $parms[KDB_FSM]->hashGet('declared_classes',$currentClassName);
                $varScope = $parms[KDB_FSM]->getVar('current_member_scope');
                $methodName = $parms[KDB_FSM]->getVar('current_member_name');

                if (!isset($metaClass['methods'])) {
                    $metaClass['methods'] = [];
                }
                if (!isset($metaClass['methods'][$methodName])) {
                    $metaClass['methods'][$methodName] = [];
                }

                $metaClass['methods'][$methodName]['scope'] = $varScope;                

                $this->hashSet('declared_classes',$currentClassName, $metaClass);
                // $parms[KDB_FSM]->setVar('current_member_scope',null);                
                // $parms[KDB_FSM]->setVar('current_member_name',null);
                $parms[KDB_FSM]->setVar('brackets_method_starting_point',$parms[KDB_FSM]->getVar('brackets'));                
                
            }
        );

        $actionStartFunction = new Action(
            function ($parms) {
                $functionName = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_function_name',$functionName);
                
                $this->pushArray('declared_function_names', $functionName);


                $metaFunction = [
                    'name' => $functionName,
                ];
                $this->hashSet('declared_functions',$functionName, $metaFunction);

            }
        );


        $actionStartFunctionBody = new Action(
            function ($parms) {
                // $functionName = $parms[KDB_TOKEN][1];
                // $parms[KDB_FSM]->setVar('current_function_name',$functionName);
                $parms[KDB_FSM]->setVar('brackets_function_starting_point',$parms[KDB_FSM]->getVar('brackets'));
                $parms[KDB_FSM]->deactivateState(self::STATE_SCRIPT_PHP_ROOT);
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

        $actionFunctionStoreReturnType = new Action(
            function ($parms) {
                $returnType = $parms[KDB_TOKEN][1];
                $functionName = $parms[KDB_FSM]->getVar('current_function_name');
                $metaFunction = $this->hashGet('declared_functions',$functionName);

                $metaFunction['return_type'] = $returnType;
                $this->hashSet('declared_functions',$functionName, $metaFunction);

            }
        );


        $actionRecordClassType = new Action(
            function ($parms) {
                $classType = $parms[KDB_TOKEN][1];
                $parms[KDB_FSM]->setVar('current_class_type',$classType);
            }
        );


        $actionRecordAbstract = new Action(
            function ($parms) {
                $parms[KDB_FSM]->setVar('current_class_abstract',true);
            }
        );

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


        $this->add(self::INITIAL_STATE, self::STARTED_SCRIPT_PHP, new ConditionTokenId(T_OPEN_TAG), $actionStarting);
        $this->add(self::INITIAL_STATE, self::STOPPED_SCRIPT_PHP, new ConditionTokenId(T_CLOSE_TAG), $actionStopping);

        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_NAMESPACE, new ConditionTokenId(T_NAMESPACE));
        $this->add(self::STATE_BUILDING_NAMESPACE, self::STATE_BUILDING_NAMESPACE, new ConditionTokenId(T_STRING), $actionBuildingNamespace);
        $this->add(self::STATE_BUILDING_NAMESPACE, self::STATE_BUILDING_NAMESPACE, new ConditionTokenId(T_NS_SEPARATOR), $actionBuildingNamespace);
        $this->add(self::STATE_BUILDING_NAMESPACE, null, new ConditionTokenLiteral(';'), $actionSaveNamespace);
    
        

        $this->add(self::INITIAL_STATE, self::INITIAL_STATE, new ConditionTokenId(T_ABSTRACT), $actionRecordAbstract);
        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_CLASS), $actionRecordClassType);
        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_INTERFACE), $actionRecordClassType);
        $this->add(self::INITIAL_STATE, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_TRAIT), $actionRecordClassType);

        $this->add(self::STATE_BUILDING_CLASS, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_STRING), $actionSaveCurrentClassName);
        
        // $this->add(self::STATE_BUILDING_CLASS, null, new ConditionTokenLiteral('{'), $actionRegisterStartingClass);
        $this->add(self::STATE_BUILDING_CLASS, self::STATE_BODY_CLASS, new ConditionTokenLiteral('{'), $actionRegisterStartingClass);

        $this->add( self::STATE_BODY_CLASS, self::STATE_START_TRAIT , new ConditionTokenId(T_USE));
        $this->add( self::STATE_START_TRAIT, self::STATE_USE_TRAIT , new ConditionTokenId(T_STRING), $actionRecordtrait);
        $this->add( self::STATE_USE_TRAIT ,self::STATE_BODY_CLASS, new ConditionTokenLiteral(';'));

        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CONST , new ConditionTokenId(T_CONST));
        $this->add( self::STATE_START_CONST, self::STATE_BUILD_CONST , new ConditionTokenId(T_STRING), $actionRecordConstName);
        $this->add( self::STATE_BUILD_CONST ,self::STATE_BUILD_CONST_VALUE, new ConditionTokenLiteral('='));
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_STRING), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_DNUMBER), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_LNUMBER), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE, self::STATE_BUILD_CONST_VALUE , new ConditionTokenId(T_CONSTANT_ENCAPSED_STRING), $actionRecordConstValue);
        $this->add( self::STATE_BUILD_CONST_VALUE ,self::STATE_BODY_CLASS, new ConditionTokenLiteral(';'));

        $this->add(self::STATE_BUILDING_CLASS, self::STATE_EXTENDING_CLASS, new ConditionTokenId(T_EXTENDS));
        $this->add(self::STATE_EXTENDING_CLASS, self::STATE_BUILDING_CLASS, new ConditionTokenId(T_STRING), $actionSaveExtensionName);
        
        $this->add(self::STATE_BUILDING_CLASS, self::STATE_IMPLEMENTS_CLASS, new ConditionTokenId(T_IMPLEMENTS));

        $this->add(self::STATE_IMPLEMENTS_CLASS, self::STATE_IMPLEMENTS_CLASS, new ConditionTokenId(T_STRING), $actionSaveInterfaceName);
        $this->add(self::STATE_IMPLEMENTS_CLASS, null, new ConditionTokenLiteral('{'), $actionRegisterStartingClass);


        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CLASS_MEMBER , new ConditionTokenId(T_PROTECTED), $actionStartClassMember);
        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CLASS_MEMBER , new ConditionTokenId(T_PUBLIC), $actionStartClassMember);
        $this->add( self::STATE_BODY_CLASS, self::STATE_START_CLASS_MEMBER , new ConditionTokenId(T_PRIVATE), $actionStartClassMember);


        $this->add( self::STATE_BODY_CLASS,self::STATE_START_CLASS_MEMBER_METHOD , new ConditionTokenId(T_FUNCTION));
        $this->add( self::STATE_START_CLASS_MEMBER,self::STATE_START_CLASS_MEMBER_METHOD , new ConditionTokenId(T_FUNCTION));
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD,self::STATE_START_CLASS_MEMBER_METHOD_PARMS , new ConditionTokenId(T_STRING), $actionStartClassMemberMethod);
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_PARMS,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenLiteral('{'), $actionSaveClassMemberMethod);
        $this->add( self::STATE_START_CLASS_MEMBER_METHOD_PARMS,self::STATE_START_CLASS_MEMBER_METHOD_BODY , new ConditionTokenLiteral(';'), $actionSaveClassMemberMethod);

        $this->add(self::STATE_START_CLASS_MEMBER_METHOD_PARMS, self::STATE_CLASS_MEMBER_METHOD_PARMS_DEFINE_RETURN_TYPE, new ConditionTokenLiteral(':'));
        $this->add(self::STATE_CLASS_MEMBER_METHOD_PARMS_DEFINE_RETURN_TYPE, self::STATE_START_CLASS_MEMBER_METHOD_PARMS, new ConditionTokenId(T_STRING), $actionSaveClassMemberMethodReturnType);
    

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


        $this->add(self::INITIAL_STATE, null,  new ConditionTokenLiteral('}'), $actionDecrementBrackets);
        $this->add(self::INITIAL_STATE, null,  new ConditionTokenLiteral('{'), $actionIncrementBrackets);


        $this->add(self::STATE_SCRIPT_PHP_ROOT, self::STATE_START_FUNCTION, new ConditionTokenId(T_FUNCTION));
        $this->add(self::STATE_START_FUNCTION, self::STATE_START_FUNCTION_PARMS, new ConditionTokenId(T_STRING), $actionStartFunction);
        
        // $this->add(self::STATE_START_FUNCTION_PARMS, self::STATE_START_FUNCTION_PARMS, new ConditionTokenLiteral('('), $actionStartFunctionBody);
        $this->add(self::STATE_START_FUNCTION_PARMS, self::STATE_START_FUNCTION_BODY, new ConditionTokenLiteral('{'), $actionStartFunctionBody);

        $this->add(self::STATE_START_FUNCTION_PARMS, self::STATE_FUNCTION_DEFINE_RETURN_TYPE, new ConditionTokenLiteral(':'));
        $this->add(self::STATE_FUNCTION_DEFINE_RETURN_TYPE, self::STATE_START_FUNCTION_PARMS, new ConditionTokenId(T_STRING), $actionFunctionStoreReturnType);


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

        // includes
        // $this->add(self::INITIAL_STATE, self::STATE_BUILDING_,  new ConditionTokenLiteral('{'), $actionIncrementBrackets);

    }

    public function setVar($name, $value) {
        $this->vars[$name] = $value;
        return $this;
    }

    public function getVar($name, $default=null) {
        return $this->vars[$name] ?? $default;
    }

    public function processTokens(array $contents) {
        foreach ($contents as $token) {
            $this->evaluate($token);
        }
    }

    public function evaluate($token) {
        

        $this->evaluateActiveStates($token);
        $this->evaluateInitialStates($token);

    }


    public function evaluateInitialStates($token) {
        if (!isset($this->transitions[self::INITIAL_STATE])) {
            throw new \Exception("Error - no initial status");
        }
        $possibleTransitions = $this->transitions[self::INITIAL_STATE];
        $this->evaluatePossibleTransitions($token,$possibleTransitions, self::INITIAL_STATE);
    }

    protected function evaluatePossibleTransitions($token,$possibleTransitions, $currentState) {
        foreach ($possibleTransitions as $transition) {
            if ($transition->isActivated($this, $token, $currentState)) {
                $transition->advance($this, $token);
            }
        }
    }

    public function evaluateActiveStates($token) {
        // $possibleTransitions = [];

        foreach ($this->transitions as $initialState => $list) {
            if ($initialState === self::INITIAL_STATE) continue;
            if (isset($this->activeStates[$initialState]) && $this->activeStates[$initialState] ) {
                // $possibleTransitions = array_merge($possibleTransitions, $list);
                $this->evaluatePossibleTransitions($token,$list, $initialState);
            }
        }

        // print_r($transitionsToEvaluate);
        // exit;

    }


    public function add($initialState, $finalState, Condition $condition, $action=null) {
        if (!isset($this->transitions[$initialState])) {
            $this->transitions[$initialState] = [];
        }
        $transition = new Transition($initialState, $finalState, $condition, $action);
        // $conditionId = $condition->getId();
        // $target = $finalState .':'.$conditionId;
        $this->transitions[$initialState][] = $transition;
    }


    public function deactivateState($state) {
        if ($state != self::INITIAL_STATE) {
            $this->activeStates[$state] = false;
        }
        
    }

    public function activateState($state) {
        if (!is_null($state)) {
            $this->activeStates[$state] = true;
        }        
    }

    public function getArray($name, $default = null) {
            return $this->arrays[$name] ?? $default;
    }

    public function pushArray($name, $value) {
        if (!isset($this->arrays[$name])) {
            $this->arrays[$name] = [];
        }
        $this->arrays[$name][] = $value;
        return $this;
    }

    public function hashSet($group, $hash, $value) {
        if (!isset($this->hashMap[$group])) {
            $this->hashMap[$group] = [];
        }
        $this->hashMap[$group][$hash] = $value;
        return $this;
    }

    public function hashGet($group, $hash, $default=null) {
        if (!isset($this->hashMap[$group])) {
            return $default;
        }
        if (!isset($this->hashMap[$group][$hash])) {
            return $default;
        }
        return $this->hashMap[$group][$hash];
    }


    public function parse($contents) {
        $tokens = token_get_all($contents);

        $auxTokens = $tokens;
        foreach ($auxTokens as $k=>$token) {
            if (is_array($token)) {
                $auxTokens[$k][-1] = token_name($token[0]);
            }
        }
        file_put_contents('tks.txt', print_r($auxTokens, true));
        

        return $tokens;
    }

}



