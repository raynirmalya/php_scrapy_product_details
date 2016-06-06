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
class SH{
	private $numberOfCall=0;
	private $getBlankMoreThanTwoTimes=0;
	function getSHProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId){
		$mainLink=$baseUrl.$categoryKey."&page=".$pageNo;
		echo $categoryId."+++++++++++++++++++++".$mainLink."<br/>";
		$html=getResponseEbay($mainLink);
		$i=0;
		//echo $html;
		if($html!=""){
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$a = returnFirstXpathObject($xpath->query(".//div[@class='products-grid']//a"));
			$pId=$a->getAttribute("id");
			$anchs = $xpath->query(".//div[@class='products-grid']//a[text()='Shop Now']");
			foreach ($anchs as $anchs){
				$saveProductDetails=new SaveProductsData();
				$pLink=SHOPCLUES_BASE_DOMAIN.$anchs->getAttribute("href");
				echo $pLink."<br/>";
				//$pId=$anchs->getAttribute("id");
				echo $pId."<br/>";
				echo "----".$saveProductDetails->checkDataAlreadyPresent($pId,"1")."<br/>";
				if($saveProductDetails->checkDataAlreadyPresent($pId,$siteId)==0){
					echo "*************************************************<br/><br/><br/>";
					SH::getSHProductDetails($pLink,$mainLink,$siteId,$categoryId,$pId);
					echo "<br/><br/><br/>*************************************************";				
				}
			}
			$pageNo++;
			SH::getSHProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId);
		}else{
			echo "*******No More Data";
			$GLOBALS['getBlankMoreThanTwoTimes']++;
			if($GLOBALS['getBlankMoreThanTwoTimes']<3){
				SH::getSHProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId);
			}else{
				$GLOBALS['getBlankMoreThanTwoTimes']=0;
			}
		}
		return $pageNo;
	}
	
	function getSHProductDetails($url,$mainLink,$siteId,$categoryId,$pid){
		$productTitle=$imgSrc=$specCategory=$deliveredBy="";
		$deliveryCharge=$coDelivery=$oneDayDelivery=$discountPercentage=$replacement=$comingSoon=$outofStock=$ratingVal=$noOfUser=$noOfReviews=$sellPrice=$originalPrice=0;
		$specList=array();
		//echo $url."<br/>";
		$formatter=new Formatter();
		echo $url."<br/>";
		$html=sendRequest($url);
	
		//echo $html;
		if($html!=""){
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$ptitle=returnFirstXpathObject($xpath->query(".//div[@class='product-about']//h1[@itemprop='name']/text()"));
			if($ptitle){
				$productTitle=$ptitle->nodeValue;
			}
			echo "Product Title:::".$ptitle->nodeValue."<br/>";			
			
			$review=returnFirstXpathObject($xpath->query(".//div[@class='product-about']//div[@class='reviews']//div[@class='review']"));
			if(review){
				preg_match('/Ratings:(\s*?)\\d+/i',$review->nodeValue,$m);
				$ratingVal=$formatter->getFormattedNumberOnly($m[0]);
				preg_match('/Reviews:(\s*?)\\d+/i',$review->nodeValue,$m1);
				$noOfReviews=$formatter->getFormattedNumberOnly($m1[0]);
			}
			//echo "Product Rating & Review:::".$review->nodeValue."<br/>";
			
			//$itemPrice=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pricing']//div[contains(@id,'line_list_price_')]"));
			//echo "Selling Price".$itemPrice->nodeValue."<br/>";
			$sellingPrice=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pricing']//div[contains(@id,'line_discounted_price_')]"));
			if($sellingPrice){
				$sellPrice=$formatter->getFormattedPrice($sellingPrice->nodeValue);
				echo "Selling Price".$sellPrice."<br/>";
			}
			$mrpPrice=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pricing']//div[@class='price']"));
			if($mrpPrice){
				$originalPrice=$formatter->getFormattedPrice($mrpPrice->nodeValue);
			    echo "Deal Price:::".$originalPrice."<br/>";
			}
			$productDiscount=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-discounts']//div[@id='product_save']//span[@class='off']"));
			if($productDiscount){
				$discountPercentage=$formatter->getFormattedNumberOnly($productDiscount->nodeValue);
				echo "Product Discount:::".$discountPercentage."<br/>";
			}
			//$productSave=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-discounts']//div[@id='product_save']//span[@class='you-save']"));
			//echo "Product Save:::".$productSave->nodeValue."<br/>";
			$freeHomeDelivery=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-discounts']//span[@class='free-home-delivery']"));
			//echo "Free Home Delivery:::".$freeHomeDelivery->nodeValue."<br/>";
			if (strpos($freeHomeDelivery->nodeValue,'FREE HOME DELIVERY') !== false) {
				$deliveryCharge="0";
			}
			$cDelivery=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-discounts']//span[@class='free-home-delivery']/following-sibling::*[1]"));
			//echo "Free Home Delivery:::".$freeHomeDelivery->nodeValue."<br/>";
			if (strpos($cDelivery->nodeValue,'Eligible for Cash on Delivery') !== false) {
				$coDelivery="1";
			}
			//$available=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-options']//div[@class='stock-exchange-cont']"));
			//echo "Available:::".$available->nodeValue."<br/>";
	
			//$paymentMode=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pincode-check']//div[@class='pincode_normal']"));
			//echo "Payment Mode:::".$paymentMode->nodeValue."<br/>";
	
			$featureList=$xpath->query(".//div[@class='product-features-list']");
			foreach($featureList as $feature){
				//echo "1"."<br/>";
				$specsKey="";
				$specsValue="";
				foreach($feature->getElementsByTagName('*') as $element ){
					//echo $element->nodeName;					
					if($element->nodeName=='h3'){
						$specCategory=$element->nodeValue;
						array_push($specList,$specCategory);
						echo "Category:::".$specCategory."<br/>";
					}else if($element->nodeName=='label'){
						$specsKey=str_replace(":","",$element->nodeValue);
						echo $specsKey;
					}else if($element->nodeName=='span'){
						$specsValue=$element->nodeValue;
						$specList[$specsKey]=$specsValue;
						echo ":::".$specsValue."<br/>";
					}
				}
			}
	
			
			
			$saveProductDetails=new SaveProductsData();
			$fetchCategotyDetails=new FetchCategoryData();
			
			$titleSpecs=$fetchCategotyDetails->getTitleSpecs($categoryId,$siteId);
			
			$func="get".$titleSpecs[0]."FromTitle";
			echo "####".$func."<br/>";
			$attr1=$attr2=$attr3=$attr4="";
			if(method_exists("SH",$func)){
				$attr1=SH::$func($productTitle);
				if(is_null($attr1)){
					$attr1="";
				}
				//echo "@@@@@@".$attr1;
			}
			$func="get".$titleSpecs[1]."FromTitle";
			if(method_exists("SH",$func)){
				$attr2=SH::$func($productTitle);
				if(is_null($attr2)){
					$attr2="";
				}
			}
			$func="get".$titleSpecs[2]."FromTitle";
			if(method_exists("SH",$func)){
				$attr3==SH::$func($productTitle);
				if(is_null($attr3)){
					$attr3="";
				}
			}
			$func="get".$titleSpecs[3]."FromTitle";
			if(method_exists("SH",$func)){
				$attr4==SH::$func($productTitle);
				if(is_null($attr4)){
					$attr4="";
				}
			}
			
			$attribute="";
			if($attr1!=""){
				$attribute.=$attr1."|";
			}
			if($attr2!=""){
				$attribute.=$attr2."|";
			}
			if($attr3!=""){
				$attribute.=$attr3."|";
			}
			if($attr4!=""){
				$attribute.=$attr4."|";
			}
			$attribute=trim(substr($attribute, 0,-1));
			echo "----5555".$attribute;
			$attribute=preg_replace("/(@|&|and|or|!|#|$|%|\^|\*|\(|\)|\_|\-|\+)/i","|",$attribute);
			$attribute=trim(substr($attribute, 0,-1));
			echo "----9999".$attribute;
			/***************** product_tbl insertion *****************/
			$productsObj=new Products();
			$ifProductPresent=$saveProductDetails->checkProductPresent(trim(F::formatTitle($productTitle,$attribute)." ".$attr1." ".$attr2." ".$attr3." ".$attr4));
				
			echo  "0000000".$ifProductPresent."<br/><br/>";
			if($ifProductPresent!=""){
				$productId=$ifProductPresent;
			}else{
				$productsObj->setProductTitle($formatter->getFormattedValues($productTitle));
				$productsObj->setBrand($formatter->getFormattedValues($specList['Brand']));
				$productsObj->setModel("");
				$productsObj->setRelatedTo("");
				$productsObj->setCategoryId($formatter->getFormattedValues($categoryId));
				$productId=$saveProductDetails->saveProductsBasicData($productsObj);
			}
			
			
			/************** our_product_title insertion ************************/
			$productsObj->setProductTitle(F::formatTitle($formatter->getFormattedValues($productTitle),$attribute));
			$productsObj->setProductId($formatter->getFormattedNumberOnly($productId));
			$saveProductDetails->updateProductTitle($productsObj);
			
			/***************** affiliate_product_mapping_tbl insertion *****************/
				
			$affiliateProductsDetailsObj=new AffiliateProductsDetails();
			$affiliateProductsDetailsObj->setSiteId($formatter->getFormattedValues($siteId));
			$affiliateProductsDetailsObj->setProductId($formatter->getFormattedValues($productId));
			$affiliateProductsDetailsObj->setAffiliateProductId($formatter->getFormattedValues($pid));
			
			
			
			$affiliateProductsDetailsObj->setAttribute1($attr1);
			$affiliateProductsDetailsObj->setAttribute2($attr2);
			$affiliateProductsDetailsObj->setAttribute3($attr3);
			$affiliateProductsDetailsObj->setAttribute4($attr4);
			$affiliateProductsDetailsObj->setProductLink($url);
			
			
			$affiliateProductsDetailsObj->setDeliveryCharge($formatter->getFormattedValues($deliveryCharge));
			$affiliateProductsDetailsObj->setCashOnDelivery($formatter->getFormattedValues($coDelivery));
			$affiliateProductsDetailsObj->setOneDayDelivery($formatter->getFormattedValues($oneDayDelivery));
			$affiliateProductsDetailsObj->setProbableDeliveryDate($formatter->getFormattedValues($deliveredBy));
			$affiliateProductsDetailsObj->setReplacementInDays($formatter->getFormattedValues($replacement));
			$affiliateProductsDetailsObj->setSellingPrice($formatter->getFormattedValues($sellPrice));
			$affiliateProductsDetailsObj->setOriginalPrice($formatter->getFormattedValues($originalPrice));
			$affiliateProductsDetailsObj->setDiscountInPercentage($formatter->getFormattedValues($discountPercentage));
			$affiliateProductsDetailsObj->setNoOfReviews($formatter->getFormattedValues($noOfReviews));
			$affiliateProductsDetailsObj->setRatingOutOfFive($formatter->getFormattedValues($ratingVal));
			$affiliateProductsDetailsObj->setNoOfUsers($formatter->getFormattedNumberOnly($noOfUser));
			
			$saveProductDetails->saveAffiliateProductsData($affiliateProductsDetailsObj);
			
			
			$imgSrcArr=$xpath->query(".//div[@class='jcarousel-skin']//input[@type='hidden']");
			foreach($imgSrcArr as $imgSrc){
				/***************** product_image_tbl insertion *****************/
				$productImagesObj=new ProductImages();
				$productImagesObj->setImageLink("http://cdn.shopclues.net/".$imgSrc->getAttribute("value"));
				$productImagesObj->setDimension("");
				$productImagesObj->setImagesSavedIn($formatter->getFormattedValues(""));
				$productImagesObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
				$productImagesObj->setProductId($formatter->getFormattedNumberOnly($productId));
				$saveProductDetails->saveProductImages($productImagesObj);
				echo "http://cdn.shopclues.net/".$imgSrc->getAttribute("value")."<br/>";
			}
			
			
			
			$cid="";
			foreach ($specList as $key=>$val){
				echo "11111".$key."<br/>";
				if(is_int($key)){
					/***************** specification_category_tbl insertion *****************/
					$specificationCategoriesObj=new SpecificationCategories();
					$specificationCategoriesObj->setSpecCategoryName($formatter->getFormattedValues($val));
					$specificationCategoriesObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
					$specificationCategoriesObj->setProductCategoryId($categoryId);
					$cid=$saveProductDetails->saveSpecificationCategories($specificationCategoriesObj);
				}else if(trim($key)!=""){
					/***************** specification_tbl insertion *****************/
					$specificationObj=new Specification();
					$specificationObj->setSpecCategoryId($formatter->getFormattedNumberOnly($cid));
					$specificationObj->setSiteId($formatter->getFormattedNumberOnly($productId));
					$specificationObj->setProductId($formatter->getFormattedNumberOnly($siteId));
					$specificationObj->setSpecificationKey($formatter->getFormattedValues($key));
					$specificationObj->setSpecificationValue($formatter->getFormattedValues($val));
					$saveProductDetails->saveSpecification($specificationObj);
				}
			}
			/*****************product_old_price_tbl insertion *****************/
			$productPriceObj=new ProductPrice();
			$productPriceObj->setSellingPrice($formatter->getFormattedNumberOnly($sellPrice));
			$productPriceObj->setOriginalPrice($formatter->getFormattedNumberOnly($originalPrice));
			$productPriceObj->setDiscountInPercentage($formatter->getFormattedNumberOnly($discountPercentage));
			$productPriceObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
			$productPriceObj->setProductId($formatter->getFormattedNumberOnly($productId));
			$saveProductDetails->saveProductOldPrice($productPriceObj);
				
			/*****************page_links_tbl insertion *****************/
			$pageLinksObj=new PageLinks();
			$pageLinksObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
			$pageLinksObj->setProductTitle($formatter->getFormattedValues($productTitle));
			$pageLinksObj->setProductLink($formatter->getFormattedValues($url));
			$pageLinksObj->setMainLink($formatter->getFormattedValues($mainLink));
			$pageLinksObj->setProductId($formatter->getFormattedNumberOnly($productId));
			$saveProductDetails->savePageLinks($pageLinksObj);
			/*****************product_tbl update *****************/
			
			$offer=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='products-offers']//div[contains(@class,'details')]"));
			if($offer){
				echo "Offer:::".$offer->nodeValue."<br/>";
				
				/***************** offers_tbl insertion *****************/
				$offersObj=new Offers();
				$offersObj->setOfferDetails($formatter->getFormattedValues($offer->nodeValue));
				$offersObj->setSiteId($formatter->getFormattedValues($siteId));
				$offersObj->setProductId($formatter->getFormattedValues($productId));
				$saveProductDetails->saveProductOffers($offersObj);
			}
			echo "<br/><br/>INSIDE PRODUCT URL ENDS<br/><br/>";
		}else{
			echo "No More Data";
		}
	}
	function getInternalMemoryFromTitle($product){
		$internal="";
		$product=preg_match('/\\d+(\s*?)GB/i',$product,$m);
		$internal=$m[0];
		echo $internal;
		return $internal;
	}
	function getColorFromTitle($product){
		$product=preg_match("/(\(.*\))/i",$product,$match);
		$attr=$match[0];
		$attr=str_replace("(","",$attr);
		$attr=str_replace(")","",$attr);
		$product=preg_match('/(Maroon|Red|Orange|Yellow|Olive|Green|Purple|Fuchsia|Lime|Teal|Blue|Navy|Black|Silver|Gray|White|Gold|Grey)/i',$attr,$mat);
		$arr=explode(",",$attr);
		$color=$mat[0];
		$c="";
		for($i=0;$i<count($arr);$i++){
			if (strpos($arr[$i],$color) !== false) {
				$c=$arr[$i];
			}
		}
		//echo $color;
		return $c;
	}
}

?>

