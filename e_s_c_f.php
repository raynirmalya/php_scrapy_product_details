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
$html=getResponseEbay("http://www.ebay.in/sch/Mobile-Accessories-/14416/i.html?_from=R40&_nkw=&_sac=1#seeAllAnchorLink");
//echo $html;
$dhtml=getDomHtml($html);
$xpath = new DOMXPath($dhtml);
$as = $xpath->query(".//div[@class='cat-c']//a");
foreach ($as as $a){
	$link=$a->getAttribute("href");	
	$urlArr=explode("/i.html",$link);
	$urlArr1=explode("/",$urlArr[0]);
	$afid=$urlArr1[count($urlArr1)-1];
	$catName=explode("(",$a->nodeValue);
	$cid=checkArrayContainsSubString($arr,strtolower(trim($catName[0])));
	//echo $afid."---".$catName[0]."---".$cid."<br/>";
	if($cid!=0){
		$q="insert into affiliate_category_mapping_tbl(category_id,affiliate_site_id,affiliate_category_name,affiliate_category_key)".
				"values(".$cid.",2,'".trim($catName[0])."','".$afid."')";
		echo $q."<br/>";
		//mysql_query($q);
	}else{
		echo "Not Present :::".$afid."---".$catName[0]."<br/>";
	}
}
?>