<?php 
include_once 'config/dbconfig.php';
ini_set('max_execution_time', 10000000000);
$conn=Connection::make_connection();
//$codeExampleList=array();
//$romArr=array("","8GB","16GB","32GB","64GB");
try{
	$sql="select distinct product_id from product_tbl";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$ress = $stmt->fetchAll();
	foreach($ress as $coll){
		$sqll="select * from specification_tbl where product_id='".$coll["product_id"]."' and (spec_key='Internal' or spec_key='Handset Color') ";
		$stmtt = $conn->prepare($sqll);
		$stmtt->execute();
		$res = $stmtt->fetchAll();
		$rom="";
		$color="";
		$memory="";
		$product_link="";
		foreach($res as $col){
			if($col["spec_key"]=="Internal"){
				$memory=$col["spec_value"];
				preg_match('/\\d+(\s*?)GB/i', $memory,$matches);
				$rom=$matches[0];
			}else if($col["spec_key"]=="Handset Color"){
				$color=$col["spec_value"];
			}
		}
		
		$sqll="select product_link from page_links_tbl where product_id='".$coll["product_id"]."' ";
		$stmtt = $conn->prepare($sqll);
		$stmtt->execute();
		$res = $stmtt->fetchAll();
		foreach($res as $col){
			$product_link=$col['product_link'];
		}
		$sqll="update affiliate_product_mapping_tbl set rom='".trim($rom)."',color='".trim($color)."',product_link='".trim($product_link)."' where product_id='".$coll["product_id"]."' ";
		$stmtt = $conn->prepare($sqll);
		$stmtt->execute();
		echo $sqll."<br/>";
	}
		
}catch (PDOException $e){
	echo $e."Database Error";
}

?>