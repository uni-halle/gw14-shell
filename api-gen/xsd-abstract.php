<?php

namespace mlu\groupwise\abstractions\xsd;
require_once 'common/config.php';
require_once 'common/autoloader.php';
use mlu\common;

/*
 * Generate walkable tree from xml-file
 */
$reader = new \XMLReader();
//$reader->open(implode('/',array(__gwApiServer,__gwApiBase,'xsd1.xsd')));
$reader->xml(file_get_contents(
     implode(array(__gwApiServer,__gwApiBase,'xsd1.xsd')), false,
     stream_context_create(array('ssl'=>array ('verify_peer'=>false,'verify_peer_name'=>false)))
 ));
$tree = common::xml2assoc($reader);

/**
 *  filter the tree
 */

//print_r($tree);

// tags to search for
$allowedTags=explode(' ',
        'xs:schema xs:element xs:complexType xs:simpleType xs:simpleContent xs:extension xs:sequence '.
        'xs:complexContent xs:attribute xs:restriction xs:enumeration');  //included grammar

// attributes to read out
$attrs=  explode(' ',
        'name type base minOccurs maxOccurs ref abstract nillable'
        );

// the filter function
$walker = function($node) use (&$walker,$allowedTags,$attrs) {
    //
    if(in_array($node->tag, $allowedTags)) {
        $res = (object)null;
        foreach($attrs as $attr) if(isset($node->$attr)) $res->$attr=$node->$attr;
        if(is_string (@$node->value)){
            return trim($node->value);
        } elseif(is_array(@$node->value)) {
            foreach($node->value as $sub) {
                $append=$walker($sub);
                if(!empty($append))
                    $res->{substr($sub->tag,3)}[]=$append;
            }
        }
        foreach($res as $k=>$v) {
            if(!in_array($k, array('param','method','resource'))) {
                if(is_array($v) && count($v)<2) $res->$k=$v[0];
            }
        }
        
        return $res;
    } else {
		__devmode&&common::logWrite("Skipping xsd-node by tag: $node->tag\n",STDERR);
    }
};
// generate the filtered tree
$mytree=$walker($tree[0]);

function readProperties($list,$target) {
    global $elements;
    foreach($list as $prop) {
        if(isset($prop->name)) {
            $pName=$prop->name;
            $target->{$pName}=$prop;
        } elseif(isset($prop->ref)) {
            if(isset($elements[$prop->ref])) {
                $pName=$elements[$prop->ref]->name;
                $target->{$pName}=$elements[$prop->ref];
                foreach($prop as $k=>$v) { $target->{$elements[$prop->ref]->name}->$k=$v; }
            } else {
                common::logWrite(sprintf("Missing reference: %s\n",$prop->ref),STDERR);
            }
        } else {
            __devmode&&common::logWrite("\nIgnoring: ".json_encode ($prop)."\n",STDERR);
        }
        if(isset($pName)) {
            switch ($target->$pName->type ) {
                case 'xs:long': $target->$pName->type='xs:int'; break;
            }
        }
    }
}

$elements = array();
foreach ($mytree->element as $ele)  {
    $elements[$ele->name]=$ele;
}

$selects = array();
foreach($mytree->simpleType as $st) {
    //echo json_encode($st).PHP_EOL;
    $selects[$st->name]=(object)array(
        'name'=>$st->name,
        'type'=>$st->restriction->base,
        'options'=>$st->restriction->enumeration
    );
}
$classes=array();
foreach($mytree->complexType as $ct) {
    $classes[$ct->name]=$res=(object)array('name'=>$ct->name);
    //if(isset($ct->abtract)) { $res->isAbtract=true; }
    $properties=(object)null;
    if(isset($ct->sequence) && isset ($ct->sequence->element)) {
        if(!is_array($ct->sequence->element)) {
            $ct->sequence->element = array($ct->sequence->element);
        }
        readProperties($ct->sequence->element, $properties);
    }
    if(isset($ct->complexContent)) {
        $res->extendedClass=$ct->complexContent->extension->base;
        if(isset($ct->complexContent->extension->sequence->element)) {
            if(!is_array($ct->complexContent->extension->sequence->element)) {
                $ct->complexContent->extension->sequence->element = array ($ct->complexContent->extension->sequence->element);
            }
            readProperties($ct->complexContent->extension->sequence->element, $properties);
        }
    }
    $res->properties=$properties;
}
$subClasses=array();
foreach ($classes as $k=>$v) {
    if(isset($v->extendedClass)) {
        $subClasses[]=$k;
        $classes[$v->extendedClass]->_subClasses[]=$v;
    }
}
foreach ($subClasses as $c) { unset($classes[$c]); }

shell_exec('rm '.__classpath.common::namespacePath(__gwXsdNamespace).'*');
$xsdNamespace=__gwXsdNamespace;
$headTxt=<<<header
<?php namespace $xsdNamespace;
/**
  * XSD-abstracted interfaces...
  */
header;

/*foreach($elements as $ele) {
    if(strtolower($ele->name)!=strtolower($ele->type) && $ele->name!="list") {
        $filetxt[]="abstract class $ele->name extends $ele->type {}";
        fwrite(STDERR, "$ele->name $ele->type".PHP_EOL);
    }
}
 * 
 */

//$filetxt[]="abstract class xsd_restriction {}";

common::def('xsdBase',__classpath.common::namespacePath(__gwXsdNamespace));

foreach($selects as $vali) {
    $filetxt=array("abstract class $vali->name extends \\mlu\\rest\\xsd_restriction { ");
    foreach($vali->options as $c) {
        $filetxt[]="\tconst $c='$c';";
    }
    $filetxt[]='}';
    common::write2file(xsdBase.$vali->name.'.cls.php',$headTxt,implode(PHP_EOL,$filetxt));
}

function classCode($classes,$prepend) {
    foreach($classes as $class) {
        $codeLines=array("abstract class $class->name ".(isset($class->extendedClass)?"extends $class->extendedClass":'').'{');
        foreach ($class->properties as $prop) {
            $isArray=isset($prop->maxOccurs)&&($prop->maxOccurs>1||$prop->maxOccurs==@unbounded);
            $codeLines[]="\t/**";
            $codeLines[]="\t * @var ".str_replace('xs:', '', $prop->type).($isArray?'[]':'')." \${$prop->name}";
            foreach($prop as $k=>$v) {
                if(!in_array($k, array('type','name'))) { $codeLines[]="\t * $k: $v"; }
            }
            $codeLines[]="\t */";
            $codeLines[]="\tpublic \$$prop->name;";
        }
        $codeLines[]="}".PHP_EOL;
        common::write2file(xsdBase.$class->name.'.cls.php',$prepend,implode(PHP_EOL,$codeLines));
        if(isset($class->_subClasses)) { classCode($class->_subClasses,$prepend); }
    }
}

classCode($classes, $headTxt);

