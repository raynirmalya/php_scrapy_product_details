<?php
include_once '../config/dbconfig.php';

class FetchCategoryData{
	public function getTitleSpecs($categoryId,$siteId){
		$titleSpec=new SplFixedArray(4);
		$conn=Connection::make_connection();
		try{
			$sql="SELECT * FROM `affiliate_category_mapping_tbl` WHERE `affiliate_site_id`=? and category_id=?";
			$params = array($siteId,$categoryId);
			$stmt = $conn->prepare($sql);
			$stmt->execute($params);
			$ress = $stmt->fetchAll();
	 		foreach($ress as $coll){
	 			$titleSpec=explode(",",$coll['title_specs']);
	 		}
		}catch (PDOException $e){
			echo $e."Database Error";
		}
		return $titleSpec;
	}
}

?>