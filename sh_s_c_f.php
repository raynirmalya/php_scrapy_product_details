<?php
include_once 'includes/curl.php' ;
include_once 'includes/parser_functions.php' ;
include_once 'includes/xpath.php' ;
mysql_connect("localhost","root","root");
mysql_select_db("virgindb");

function checkArrayContainsSubString($haystack, $needle){
	$check=0;
	$i=0;
	foreach ($haystack as $id=>$hay) {
		  
		  if($i==12){
		  	
		  	// echo $hay."***".$needle."***".strpos($needle,$hay)."<br/>";
		  }
		if (strpos($needle,$hay) !== FALSE || strpos($hay,$needle) !== FALSE) {
			
			//echo "***".$hay."***".$needle."<br/>";
			$check=$id;
			break;
		}
		$i++;
	}
	return $check;
}

$q="select category_id,category_name from product_categories_tbl where parent_id=4";
$r=mysql_query($q);
$arr=array();
while($rec=mysql_fetch_array($r)){
	$arr[$rec["category_id"]]=strtolower($rec["category_name"]);
}

print_r($arr);
$html=sendRequest("http://www.shopclues.com/electronic-accessories-8/mobile-and-tablet-accessories-6.html");
//echo $html;
$dhtml=getDomHtml($html);
$xpath = new DOMXPath($dhtml);
$as = $xpath->query(".//ul[@class='filter-category']//li[@class='child-two']//a");
foreach ($as as $a){
	$link=$a->getAttribute("href");
	echo $link."<br/>";	
	$urlArr=explode(".html",$link);
	$urlArr1=explode("-",$urlArr[0]);
	$afid=$urlArr1[count($urlArr1)-1];
	$catName=explode("(",$a->nodeValue);
	$cName=str_replace("Â", "", $catName[0]);
	$cid=checkArrayContainsSubString($arr,strtolower(trim($cName)));
	echo $afid."---".$cName."---".$cid."<br/>";
	if($cid!=0){
		$q="insert into affiliate_category_mapping_tbl(category_id,affiliate_site_id,affiliate_category_name,affiliate_category_key)".
				"values(".$cid.",4,'".trim($cName)."','".$afid."')";
		echo $q."<br/>";
		mysql_query($q);
	}else{
		echo "Not Present :::".$afid."---".$cName."<br/>";
	}
}
?>