<?php
include_once 'includes/curl.php' ;
include_once 'includes/parser_functions.php' ;
include_once 'includes/xpath.php' ;
mysql_connect("localhost","root","root");
mysql_select_db("virgindb");
$html=sendRequest("http://www.flipkart.com/mobile-accessories/pr?p%5B%5D=sort%3Drecency_desc&sid=tyy%2C4mr&filterNone=true");
//echo $html;
$dhtml=getDomHtml($html);
$xpath = new DOMXPath($dhtml);
$anchs = $xpath->query(".//div[@id='substores']//li[contains(@class,'store') and not(contains(@class,'parent'))]//a");
foreach ($anchs as $anch){
	$val=explode("(",$anch->nodeValue);
	$link=$anch->getAttribute("href");
	$arr=explode("sid=", $link);
	$arr1=explode("&", $arr[1]);
	$sid=$arr1[0];
	$q="select * from product_categories_tbl where category_name like'".trim($val[0])."'";
	$r=mysql_query($q);
	while($rec=mysql_fetch_array($r)){
		$category_id=$rec["category_id"];
	}
	$q="insert into affiliate_category_mapping_tbl(category_id,affiliate_site_id,affiliate_category_name,affiliate_category_key)".
		 "values(".$category_id.",1,'".trim($val[0])."','".$sid."')";
	echo $q."<br/>";
	//mysql_query($q);
	//echo $sid."<br/>";
}
?>