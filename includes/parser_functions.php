<?php
libxml_use_internal_errors(true);
function getPosition($obj,$attribute,$attributeName){
    return  strpos($obj->getAttribute($attribute),$attributeName);
}
function getDomHtml($html){
    $domHtml= new DOMDocument();
    $domHtml->loadHtml($html);
    return  $domHtml;
}
function hasAttrStartWith($obj,$attr,$attrName){
	$atr=$obj->getAttribute($attr);
	if(strpos($atr,$attrName)===0){
		return true;
	}else{
		return false;
	}
}
function getClosestTag($obj,$needle){
	$i=0;
	$tagObj="";
	foreach ($obj->getElementsByTagName($needle) as $nObj){
		if($i==0){
		  $tagObj=$nObj;
		  break;
		}
	}
	return $tagObj;
}
function exactAttrMatch($obj,$attr,$attrName){
	if($obj->getAttribute($attr)==$attrName){
		return true;
	}else{
		return false;
	}
}
?>