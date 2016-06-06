<?php 
include_once '../config/constants.php';
include_once '../includes/curl.php';
include_once '../includes/parser_functions.php';
include_once '../includes/xpath.php';
include_once '../com/formatter/f_formatter.php';

include_once '../model/AffiliateProductsDetails.php';
include_once '../model/Offers.php';
include_once '../model/PageLinks.php';
include_once '../model/ProductImages.php';
include_once '../model/ProductPrice.php';
include_once '../model/Products.php';
include_once '../model/Specification.php';
include_once '../model/SpecificationCategories.php';

include_once '../dao/SaveProductsData.php';
include_once '../dao/FetchCategoryData.php';
error_reporting(1);
class P{
	private $numberOfCall=0;
	private $getBlankMoreThanTwoTimes=0;
	function getPProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId,$urlPart,$urlSecondPart){
		$mainLink=$baseUrl.$urlSecondPart."?page_count=".$pageNo;
		echo $mainLink;
		$json=sendRequest($mainLink);
		if($json!="{}"){
			$json=json_decode($json);
			$grid=$json->grid_layout;
			for($i=0;$i<1;$i++){
				$productId=$grid[$i]->complex_product_id;
				$url=$grid[$i]->url;				
				$saveProductDetails=new SaveProductsData();
				echo "******".$productId."<br/>";
				if($saveProductDetails->checkDataAlreadyPresent($productId,$siteId)==0){
					echo "*************************************************<br/><br/><br/>";
					P::getPProductDetails($url,$mainLink,$siteId,$categoryId);
					echo "<br/><br/><br/>*************************************************";
				}
				echo $productId."::".$url."<br/>";	
			}
			
			$pageNo++;
			P::getPProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId,$urlPart,$urlSecondPart);
		}else{
			echo "*******No More Data";
			$GLOBALS['getBlankMoreThanTwoTimes']++;
			if($GLOBALS['getBlankMoreThanTwoTimes']<3){
				P::getPProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId,$urlPart,$urlSecondPart);
			}else{
				$GLOBALS['getBlankMoreThanTwoTimes']=0;
			}
		}
		//return ((($pageNo-1)*FLIPKART_PERPAGE_ITEM)+$pageNo);
	}
	function getPProductDetails($url,$mainLink,$siteId,$categoryId){
		$pid=$productTitle=$imgSrc=$specCategory=$deliveredBy="";
		$deliveryCharge=$coDelivery=$oneDayDelivery=$discountPercentage=$replacement=$comingSoon=$outofStock=$ratingVal=$noOfUser=$noOfReviews=$sellPrice=$originalPrice=0;
		$specList=array();
		//echo $url."<br/>";
		$formatter=new Formatter();
		$html=sendRequest($url);
	    echo $html;
		if($html!="{}"){
			$json=json_decode($html);
			$pid=$json->product_id;
			echo $pid."<br/>";
			$productTitle=$json->name;
			echo $productTitle."<br/>";
			$images=$json->other_images;
			echo $json->image_url."<br/>";
			for($i=0;$i<count($images);$i++){
				echo $images[$i]."<br/>";
			}
			$long_rich_desc=$json->long_rich_desc;
			for($i=0;$i<count($long_rich_desc);$i++){
				echo $long_rich_desc[$i]->title."<br/>";
				$attributes=$long_rich_desc[$i]->attributes;
				if($attributes){
					foreach ($attributes as $j=>$v){
						echo $j.":::".$v."<br/>";
					}
				}
			}
			echo $json->actual_price."<br/>";
			echo $json->offer_price."<br/>";
			echo $json->tag."<br/>";
			$pay_type_supported=$json->pay_type_supported;
			//print_r($json);
		}else{
			echo "No More Data";
		}
		
	}
}

?>