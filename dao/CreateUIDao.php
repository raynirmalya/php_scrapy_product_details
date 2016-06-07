<?php 
include_once '../config/dbconfig.php';
class CreateUIDao{
	 public function getCategoryList($parentId,$siteId){
	 	$conn=Connection::make_connection();
	 	$categoryDetails=array();
	 	try{
	 		$sql="select * from  affiliate_category_mapping_tbl where category_id in (SELECT category_id FROM `product_categories_tbl` where parent_id=?) and affiliate_site_id=?";
	 		$stmt = $conn->prepare($sql);
	 		$stmt->execute(array($parentId,$siteId));
	 		$ress = $stmt->fetchAll();
	 		foreach($ress as $coll){
	 			$id=$coll['category_id'];
	 			$affiliateCategoryName=$coll['affiliate_category_name'];
	 			$affiliateCategoryKey=$coll['affiliate_category_key'];
	 			$urlPart=$coll['url_part'];
	 			$categoryDetail=array("categoryId"=>$id,
	 					"affiliateCategoryName"=>$affiliateCategoryName,
	 					"affiliateCategoryKey"=>$affiliateCategoryKey,"urlPart"=>$urlPart);
	 			array_push($categoryDetails, $categoryDetail);
	 		}
	 	}catch (PDOException $e){
	 		echo $e."Database Error";
	 	}
	 	return json_encode($categoryDetails);
	 }
	 
	 public function getSiteList(){
	 	$siteDetails=array();
	 	$conn=Connection::make_connection();
	 	try{
	 		$sql="SELECT * FROM `affiliate_sites`";
	 		$stmt = $conn->prepare($sql);
	 		$stmt->execute();
	 		$ress = $stmt->fetchAll();
	 		foreach($ress as $coll){
	 			$id=$coll['id'];
	 			$affiliateSiteName=$coll['affiliate_site_name'];
	 			$apiKey=$coll['api_key'];
	 			$baseUrl=$coll['base_url'];
	 			$new=$coll['new'];
	 			$popular=$coll['popular'];
	 			$high=$coll['high'];
	 			$low=$coll['low'];
	 			$relevant=$coll['relevant'];
	 			$discount=$coll['discount'];
	 			$siteDetail=array("id"=>$id,"affiliateSiteName"=>$affiliateSiteName,
	 					"apiKey"=>$apiKey,"baseUrl"=>$baseUrl,
	 					"latest"=>$new,"popular"=>$popular,
	 					"high"=>$high,"low"=>$low,
	 					"relevant"=>$relevant,"discount"=>$discount);
	 			array_push($siteDetails, $siteDetail);
	 		}
	 	}catch (PDOException $e){
	 		echo $e."Database Error";
	 	}
	 	return json_encode($siteDetails);
	 }
	 
	 public function getLink($siteId){
	 	$siteLinks=array();
	 	$conn=Connection::make_connection();
	 	try{
	 		$sql="SELECT * FROM `affiliate_sites` where id=?";
	 		$stmt = $conn->prepare($sql);
	 		$stmt->execute(array($siteId));
	 		$ress = $stmt->fetchAll();
	 		foreach($ress as $coll){
	 			$productBaseUrl=$coll['product_base_url'];
	 			$baseUrl=$coll['base_url'];
	 			$new=$coll['new'];
	 			$popular=$coll['popular'];
	 			$high=$coll['high'];
	 			$low=$coll['low'];
	 			$relevant=$coll['relevant'];
	 			$discount=$coll['discount'];
	 			$siteLink=array("apiKey"=>$apiKey,
	 					"productBaseUrl"=>$productBaseUrl,"baseUrl"=>$baseUrl,
	 					"latest"=>$new,"popular"=>$popular,
	 					"high"=>$high,"low"=>$low,
	 					"relevant"=>$relevant,"discount"=>$discount);
	 			array_push($siteLinks, $siteLink);
	 		}
	 	}catch (PDOException $e){
	 		echo $e."Database Error";
	 	}
	 	return json_encode($siteLinks);
	 }
	
}


?>