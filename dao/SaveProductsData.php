<?php
include_once '../model/AffiliateProductsDetails.php';
include_once '../model/Offers.php';
include_once '../model/PageLinks.php';
include_once '../model/ProductImages.php';
include_once '../model/ProductPrice.php';
include_once '../model/Products.php';
include_once '../model/Specification.php';
include_once '../model/SpecificationCategories.php';
include_once '../config/dbconfig.php';

class SaveProductsData{
	function checkProductPresent($productTitle){
		$productId="";
		$conn=Connection::make_connection();
		try{
			$sql="select * from (SELECT concat(pt.our_product_title,' ',apmt.attribute1,' ',apmt.attribute2,' ',apmt.attribute3,' ',apmt.attribute4) 
					as product_title,pt.product_id FROM `product_tbl` as pt, `affiliate_product_mapping_tbl` apmt  
					WHERE pt.product_id=apmt.product_id group by apmt.product_id) as s where s.product_title=? limit 0,1";
			$stmt = $conn->prepare($sql);
			$stmt->execute(array($productTitle));
			$ress = $stmt->fetchAll();
			foreach($ress as $coll){
				$productId=$coll['product_id'];
			}
		}catch (PDOException $e){
			echo $e."Database Error";
		}
		return $productId;
	}
	function checkDataAlreadyPresent($pid,$siteId){
		$count=0;
		$conn=Connection::make_connection();
		try{
			$sql="SELECT * FROM `affiliate_product_mapping_tbl` WHERE `affiliate_product_id`=? and site_id=?";
			$params = array($pid,$siteId);
			$stmt = $conn->prepare($sql);
			$stmt->execute($params);
			$count=$stmt->rowCount();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
		return $count;
	}

	function saveProductsBasicData($obj){
		$conn=Connection::make_connection();
		$productId="";
		$ourProductTitle="";
		try{
			$sql="insert into product_tbl(product_title,our_product_title,brand,model,related_to,category_id)
				  value(?,?,?,?,?,?)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(1, $obj->getProductTitle());
			$stmt->bindParam(2, $ourProductTitle);
			$stmt->bindParam(3, $obj->getBrand());
			$stmt->bindParam(4, $obj->getModel());
			$stmt->bindParam(5, $obj->getRelatedTo());
			$stmt->bindParam(6, $obj->getCategoryId());
			$stmt->execute();
			$productId=$conn->lastInsertId();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
		return $productId;
	}
	function updateProductTitle($obj){
		$conn=Connection::make_connection();
		$productId="";
		try{
			$sql="update product_tbl set our_product_title=? where product_id=? ";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(1, $obj->getProductTitle());
			$stmt->bindParam(2, $obj->getProductId());
			$stmt->execute();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
	}
	function saveAffiliateProductsData($obj){
		$conn=Connection::make_connection();
		try{
			$sql="insert into affiliate_product_mapping_tbl(site_id,product_id,affiliate_product_id,".
			"attribute1,attribute2,attribute3,attribute4,product_link,delivery_charge,cash_on_delivery,".
			"one_day_delivery,probable_delivery_date,replacement_in_days,".
			"selling_price,original_price,discount_in_percentage,no_of_reviews,rating_out_of_five,no_of_users)
				  value(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			echo $obj->getNoOfReviews()."----"."<br/>";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(1, $obj->getSiteId());
			$stmt->bindParam(2, $obj->getProductId());
			$stmt->bindParam(3, $obj->getAffiliateProductId());
			
			$stmt->bindParam(4, $obj->getAttribute1());
			$stmt->bindParam(5, $obj->getAttribute2());
			$stmt->bindParam(6, $obj->getAttribute3());
			$stmt->bindParam(7, $obj->getAttribute4());
			$stmt->bindParam(8, $obj->getProductLink());
			
			$stmt->bindParam(9, $obj->getDeliveryCharge());
			$stmt->bindParam(10, $obj->getCashOnDelivery());
			$stmt->bindParam(11, $obj->getOneDayDelivery());
			$stmt->bindParam(12, $obj->getProbableDeliveryDate());
			$stmt->bindParam(13, $obj->getReplacementInDays());
			$stmt->bindParam(14, $obj->getSellingPrice());
			$stmt->bindParam(15, $obj->getOriginalPrice());
			$stmt->bindParam(16, $obj->getDiscountInPercentage());
			$stmt->bindParam(17, $obj->getNoOfReviews());
			$stmt->bindParam(18, $obj->getRatingOutOfFive());
			$stmt->bindParam(19, $obj->getNoOfUsers());
			$stmt->execute();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
	}
	function saveProductOffers($obj){
		$conn=Connection::make_connection();
		try{
			$sql="insert into offers_tbl(offer_details,site_id,product_id)
				  value(?,?,?)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(1, $obj->getOfferDetails());
			$stmt->bindParam(2, $obj->getSiteId());
			$stmt->bindParam(3, $obj->getProductId());
			$stmt->execute();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
	}
	
	function saveProductImages($obj){
		$conn=Connection::make_connection();
		try{
			$sql="insert into product_image_tbl(image_link,dimension,images_saved_in,site_id,product_id)
				  value(?,?,?,?,?)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(1, $obj->getImageLink());
			$stmt->bindParam(2, $obj->getDimension());
			$stmt->bindParam(3, $obj->getImagesSavedIn());
			$stmt->bindParam(4, $obj->getSiteId());
			$stmt->bindParam(5, $obj->getProductId());
			$stmt->execute();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
	}
	function saveSpecificationCategories($obj){
		$cid="";
		$conn=Connection::make_connection();
		try{
			$pcid=$obj->getProductCategoryId();
			$specCategoryName=$obj->getSpecCategoryName();
			$siteId=$obj->getSiteId();
			$sql="SELECT id FROM `specification_category_tbl` WHERE product_category_id=? and site_id=? and spec_category_name=?";
			$params = array($pcid,$siteId,$specCategoryName);
			$stmt = $conn->prepare($sql);
			$stmt->execute($params);
			$ress = $stmt->fetchAll();
			foreach($ress as $coll){
				$cid=$coll['id'];
			}
			$count=$stmt->rowCount();
			if($count<1){
				$sql="insert into specification_category_tbl(spec_category_name,site_id,product_category_id)
					  value(?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(1, $obj->getSpecCategoryName());
				$stmt->bindParam(2, $siteId);
				$stmt->bindParam(3, $pcid);
				$stmt->execute();
				$cid=$conn->lastInsertId();
			}
		}catch (PDOException $e){
			echo $e."Database Error";
		}
		return $cid;
	}
	function saveSpecification($obj){
		$conn=Connection::make_connection();
		$specKey="";
		$specKey=$obj->getSpecificationKey();
		if(trim($specKey)!=""){
			try{
				$sql="insert into specification_tbl(spec_category_id,product_id,site_id,spec_key,spec_value)
					  value(?,?,?,?,?)";
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(1, $obj->getSpecCategoryId());
				$stmt->bindParam(2, $obj->getSiteId());
				$stmt->bindParam(3, $obj->getProductId());
				$stmt->bindParam(4, $specKey);
				$stmt->bindParam(5, $obj->getSpecificationValue());
				$stmt->execute();
			}catch (PDOException $e){
				echo $e."Database Error";
			}
		}
	}
	function saveProductOldPrice($obj){
		$conn=Connection::make_connection();
		try{
			$sql="insert into product_old_price_tbl(selling_price,original_price,discount,site_id,product_id)
				  value(?,?,?,?,?)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(1, $obj->getSellingPrice());
			$stmt->bindParam(2, $obj->getOriginalPrice());
			$stmt->bindParam(3, $obj->getDiscountInPercentage());
			$stmt->bindParam(4, $obj->getSiteId());
			$stmt->bindParam(5, $obj->getProductId());
			$stmt->execute();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
	}
	function savePageLinks($obj){
		$conn=Connection::make_connection();
		try{
			$sql="insert into page_links_tbl(site_id,product_title,product_link,main_link,product_id)
				  value(?,?,?,?,?)";
			$stmt = $conn->prepare($sql);
			$stmt->bindParam(1, $obj->getSiteId());
			$stmt->bindParam(2, $obj->getProductTitle());
			$stmt->bindParam(3, $obj->getProductLink());
			$stmt->bindParam(4, $obj->getMainLink());
			$stmt->bindParam(5, $obj->getProductId());
			$stmt->execute();
		}catch (PDOException $e){
			echo $e."Database Error";
		}
	}
}
?>