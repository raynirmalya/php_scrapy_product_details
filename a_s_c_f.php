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
$html=sendRequest("http://www.amazon.in/s/ref=sr_nr_n_1?fst=as%3Aoff&rh=n%3A1389402031%2Ck%3Amobile+accessories&keywords=mobile+accessories&ie=UTF8&qid=1442692762&rnid=3576079031");
//echo $html;
$dhtml=getDomHtml($html);
$xpath = new DOMXPath($dhtml);
$as = $xpath->query(".//div[@class='categoryRefinementsSection']//li[not(contains(@class,'shoppingEngineExpand'))]//a");
foreach ($as as $a){
	$link=$a->getAttribute("href");
	//echo $link."<br/>";	
	$urlArr=explode("&rh=",$link);
	$urlArr1=explode("&",$urlArr[1]);
	$afid=explode("k%3A",$urlArr1[0])[0];
	$catName=explode("(",$a->nodeValue);
	$cName=str_replace("Â", "", $catName[0]);
	$cid=checkArrayContainsSubString($arr,strtolower(trim($cName)));
	echo $afid."---".$cName."---".$cid."<br/>";
	if($cid!=0){
		$q="insert into affiliate_category_mapping_tbl(category_id,affiliate_site_id,affiliate_category_name,affiliate_category_key)".
				"values(".$cid.",4,'".trim($cName)."','".$afid."')";
		echo $q."<br/>";
		//mysql_query($q);
	}else{
		echo "Not Present :::".$afid."---".$cName."<br/>";
	}
}
?>