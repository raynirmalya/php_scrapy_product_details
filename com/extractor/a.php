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

class A{
	private $numberOfCall=0;
	private $getBlankMoreThanTwoTimes=0;
	function getAProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId){ 
		$mainLink=$baseUrl.$pageNo."?rh=".$categoryKey."&page=".$pageNo;
		echo $categoryId."+++++++++++++++++++++".$mainLink."<br/>";
		$html=getResponseEbay($mainLink);
		$i=0;
		//echo $html;
		if($html!=""){
			$dhtml=getDomHtml($html);
			foreach($dhtml->getElementsByTagName('div') as $div){
				if(exactAttrMatch($div, "id", "mainResults") || exactAttrMatch($div, "id", "atfResults")){
					foreach($dhtml->getElementsByTagName('div') as $dv){
						if(exactAttrMatch($dv, "class", "s-item-container")){
							$saveProductDetails=new SaveProductsData();
							$a=getClosestTag($dv,"a");
							if($i<11){
							echo $a->getAttribute("href")."<br/>";
							echo A::getAPid($a->getAttribute("href"))."<br/>";
							echo "----".$saveProductDetails->checkDataAlreadyPresent(A::getAPid($a->getAttribute("href")),"1")."<br/>";
								if($saveProductDetails->checkDataAlreadyPresent(A::getAPid($a->getAttribute("href")),$siteId)==0){
									echo "*************************************************<br/><br/><br/>";
									A::getAProductDetails($a->getAttribute("href"),$mainLink,$siteId,$categoryId);
									echo "<br/><br/><br/>*************************************************";
								}
							}
							$i++;
						}
					}
				}
			}
			$pageNo++;
			A::getAProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId);
		}else{
			echo "*******No More Data";
			$GLOBALS['getBlankMoreThanTwoTimes']++;
			if($GLOBALS['getBlankMoreThanTwoTimes']<3){
				A::getAProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId);
			}else{
				$GLOBALS['getBlankMoreThanTwoTimes']=0;
			}
		}
		return $pageNo;
	}
	function getAPid($str){
		$arr=explode("/",$str);
		$pid=$arr[5];
		return $pid;
	}
	function getAProductDetails($url,$mainLink,$siteId,$categoryId){
		$pid=$productTitle=$imgSrc=$specCategory=$deliveredBy="";
		$deliveryCharge=$coDelivery=$oneDayDelivery=$discountPercentage=$replacement=$comingSoon=$outofStock=$ratingVal=$noOfUser=$noOfReviews=$sellPrice=$originalPrice=0;
		$specList=array();
		$specList[0]="GENERAL";
		$formatter=new Formatter();
		//echo $url."<br/>";
		$html=sendRequest($url);
		//echo $html;
		if($html!=""){
			echo "<br/><br/>INSIDE PRODUCT URL STARTS<br/><br/>";
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$pid=A::getAPid($url);
			$pdTab=0;
			$ptitle=returnFirstXpathObject($xpath->query(".//div[contains(@id,'titleSection')]//span[contains(@id,'productTitle')]"));
			$productTitle=$ptitle->nodeValue;
			echo $productTitle."<br/>";
			
			$saveProductDetails=new SaveProductsData();			
			$fetchCategotyDetails=new FetchCategoryData();
			
			$titleSpecs=$fetchCategotyDetails->getTitleSpecs($categoryId,$siteId);
			
			$func="get".$titleSpecs[0]."FromTitle";
			echo "####".$func."<br/>";
			$attr1=$attr2=$attr3=$attr4="";
			if(method_exists("A",$func)){
				$attr1=A::$func($productTitle);
				if(is_null($attr1)){
					$attr1="";
				}
				//echo "@@@@@@".$attr1;
			}
			$func="get".$titleSpecs[1]."FromTitle";
			if(method_exists("A",$func)){
				$attr2=A::$func($productTitle);
				if(is_null($attr2)){
					$attr2="";
				}
			}			
			$func="get".$titleSpecs[2]."FromTitle";
			if(method_exists("A",$func)){
				$attr3==A::$func($productTitle);
				if(is_null($attr3)){
					$attr3="";
				}
			}
			$func="get".$titleSpecs[3]."FromTitle";
			if(method_exists("A",$func)){
				$attr4==A::$func($productTitle);
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
				$productsObj->setBrand("");
				$productsObj->setModel($formatter->getFormattedValues($specList['Item model number']));
				$productsObj->setRelatedTo("");
				$productsObj->setCategoryId($formatter->getFormattedValues($categoryId));
				$productId=$saveProductDetails->saveProductsBasicData($productsObj);
			}
			
			
			//$images=returnFirstXpathObject($xpath->query(".//div[contains(@class,'imgTagWrapper')]//img"));
			
			
			$ratingDv=returnFirstXpathObject($xpath->query(".//div[contains(@id,'averageCustomerReviews')]//span[contains(@class,'reviewCountTextLinkedHistogram')]"));
			if($ratingDv){
				$ratingVal=$formatter->getFormattedNumberOnly($ratingDv->getAttribute("title"));
				echo "+++".$formatter->getFormattedNumberOnly($ratingVal)."<br/>";
			}
			$reviewSpn=returnFirstXpathObject($xpath->query(".//div[contains(@id,'averageCustomerReviews')]//a[contains(@id,'acrCustomerReviewLink')]"));
			if($reviewSpn){
				$noOfReviews=$formatter->getFormattedNumberOnly($reviewSpn->nodeValue);
				echo "+++".$formatter->getFormattedNumberOnly($noOfReviews)."<br/>";
			}
			$mrpSpn=returnFirstXpathObject($xpath->query(".//td[contains(@class,'a-text-strike')]"));
			if($mrpSpn)
				$originalPrice=$formatter->getFormattedPrice($mrpSpn->nodeValue);
			echo "Original Price:::".$originalPrice."<br/>";
				
			$spriceSpn=returnFirstXpathObject($xpath->query(".//div[contains(@id,'price')]//span[contains(@id,'priceblock_saleprice')]"));
			if($spriceSpn)
				$sellPrice=$formatter->getFormattedPrice($spriceSpn->nodeValue);
			echo "Selling Price:::".$sellPrice."<br/>";
			$discountPercentage=round((($originalPrice-$sellPrice)/$originalPrice)*100);
			echo $discountPercentage."<br/>";
			$shippingSpn="";
			$shippingSpn=returnFirstXpathObject($xpath->query(".//span[contains(@id,'saleprice_shippingmessage')]"));
			if($shippingSpn)
				$shippingSpn=$shippingSpn->nodeValue;
			if (strpos($shippingSpn,'FREE Delivery') !== false) {
				$deliveryCharge="0";
			}
			if (strpos($shippingSpn,'Cash on Delivery eligible') !== false) {
				$coDelivery="1";
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
			
			
			
			$arr=explode("'colorImages': { 'initial': ",$html);
			$arr1=explode("'colorToAsin'",$arr[1]);
			//echo substr(trim($arr1[0]),0,-2)."----"."<br/>";
			$imgObj=substr(trim($arr1[0]),0,-2);
			$imgObj=json_decode($imgObj);
			for($i=0;$i<count($imgObj);$i++){
				foreach ($imgObj[$i]->main as $key=>$val){
					/***************** product_image_tbl insertion *****************/
					$productImagesObj=new ProductImages();
					$productImagesObj->setImageLink($key);
					$productImagesObj->setDimension($val[0]."x".$val[1]);
					$productImagesObj->setImagesSavedIn($formatter->getFormattedValues(""));
					$productImagesObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
					$productImagesObj->setProductId($formatter->getFormattedNumberOnly($productId));
					$saveProductDetails->saveProductImages($productImagesObj);
					echo $key.":::".$val[0]."x".$val[1]."<br/>";
				}
				echo "<br/>";
			}
			
			foreach($dhtml->getElementsByTagName('div') as $d){
							$tdCntr=0;
							if(exactAttrMatch($d,"class","section techD")){
								foreach ($d->getElementsByTagName('table') as $tbl){
									//echo $pdTab."**&&**";
									if($pdTab==0){
										foreach($tbl->getElementsByTagName('tr') as $tr){
											$specsKey="";
											$specsValue="";
											foreach($tr->getElementsByTagName('td') as $td){
												if(exactAttrMatch($td, "class", "label") || exactAttrMatch($td, "class", "value")){
													if($tdCntr%2==0){
														$specsKey=$td->nodeValue;													
														//var_dump(trim($specsKey));	
														//if (substr_count(trim($specsKey), '&nbsp;') > 0)  {
															echo $td->nodeValue.":::";
														//}
													}else{
														//if(trim($specsValue)!="&nbsp;"){											
															$specsValue=$td->nodeValue;
															$specList[$specsKey]=$specsValue;
															echo $td->nodeValue;
														//}
													}
													$tdCntr++;
												}
											}
											echo "<br/>";
										}
									}
									$pdTab++;
								}
								
							}
						//}
					//}
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
			echo "<br/><br/>INSIDE PRODUCT URL ENDS<br/><br/>";
		}else{
			echo "No More Data";
		}
		
		$saveProductDetails=new SaveProductsData();
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
	/*function getColorFromTitle($product){
		$color="";
		$product=preg_match("/(\(.*\))/i",$product,$match);
		$attr=$match[0];
		$attr=str_replace("(","",$attr);
		$attr=str_replace(")","",$attr);
		$product=preg_match('/(Maroon|Red|Orange|Yellow|Olive|Green|Purple|Fuchsia|Lime|Teal|Blue|Navy|Black|Silver|Gray|White)/i',$attr,$mat);
		$color=$mat[0];
		echo $color;
		return $color;
	}*/
}
?>