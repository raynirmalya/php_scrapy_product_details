<?php
function returnFirstXpathObject($objArr){
	$i=0;
	$fobj="";
	foreach ($objArr as $obj){
		if($i==0){
			$fobj=$obj;
			$i++;
		}else{
			break;
		}
	}
	return $fobj;
}
function getTextRemovingOtherTag($obj){
	$childrenNodes=$obj->childNodes;
	echo $childrenNodes->length;
	while ($childrenNodes->length > 0) {
		$obj->removeChild($childrenNodes->item(0));
	}
	return $obj;
}
?>