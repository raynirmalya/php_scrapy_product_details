<?php
include_once 'config/constants.php';
include_once 'includes/curl.php';
include_once 'includes/parser_functions.php';
include_once 'includes/xpath.php';
function getProductUrls($pageNo){
	echo SHOPCLUES_XHR_URL.$pageNo."<br/>";
	$html=sendRequest(SHOPCLUES_XHR_URL.$pageNo);
	//echo $html;	
	$i=0;
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		$xpath = new DOMXPath($dhtml);
		$anchs = $xpath->query(".//div[@class='products-grid']//a[text()='Shop Now']");
		foreach ($anchs as $anchs){
			//echo $anchs->getAttribute("href")."";
			//if($i<3){
				echo SHOPCLUES_BASE_DOMAIN.$anchs->getAttribute("href")."<br/>";
				echo "*************************************************<br/><br/><br/>";
				getProductDetails(SHOPCLUES_BASE_DOMAIN.$anchs->getAttribute("href"));
				echo "<br/><br/><br/>*************************************************";
			//}
			//$i++;
		}
		//print_r($els);
	}else{
		echo "No More Data";
	}
	return ((($pageNo-1)*SNAPDEAL_PERPAGE_ITEM)+$pageNo);
}

function getProductDetails($url){
	//echo $url."<br/>";
	$html=sendRequest($url);
	
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		$xpath = new DOMXPath($dhtml);		
		$ptitle=returnFirstXpathObject($xpath->query(".//div[@class='product-about']//h1[@itemprop='name']/text()"));
		echo "Product Title:::".$ptitle->nodeValue."<br/>";
		$review=returnFirstXpathObject($xpath->query(".//div[@class='product-about']//div[@class='reviews']//div[@class='review']"));
		echo "Product Rating & Review:::".$review->nodeValue."<br/>";
		$imgSrcArr=$xpath->query(".//div[@class='jcarousel-skin']//input[@type='hidden']");
		foreach($imgSrcArr as $imgSrc){
			echo "http://cdn.shopclues.net/".$imgSrc->getAttribute("value")."<br/>";
		}
		$itemPrice=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pricing']//div[contains(@id,'line_list_price_')]"));
		echo "Selling Price".$itemPrice->nodeValue."<br/>";
		$sellingPrice=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pricing']//div[contains(@id,'line_discounted_price_')]"));
		echo "Selling Price".$sellingPrice->nodeValue."<br/>";
		$sellingPrice=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pricing']//div[@class='price']"));
		echo "Deal Price:::".$sellingPrice->nodeValue."<br/>";
		$productDiscount=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-discounts']//div[@id='product_save']//span[@class='off']"));
		echo "Product Discount:::".$productDiscount->nodeValue."<br/>";
		$productSave=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-discounts']//div[@id='product_save']//span[@class='you-save']"));
		echo "Product Save:::".$productSave->nodeValue."<br/>";
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
		echo $deliveryCharge.":::".$coDelivery.$cDelivery->nodeValue;
		$offer=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='products-offers']//div[contains(@class,'details')]"));
		echo "Offer:::".$offer->nodeValue."<br/>";
		$available=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-options']//div[@class='stock-exchange-cont']"));		
		echo "Available:::".$available->nodeValue."<br/>";
		
		$paymentMode=returnFirstXpathObject($xpath->query(".//div[@class='product-details']//div[@class='product-pincode-check']//div[@class='pincode_normal']"));
		echo "Payment Mode:::".$paymentMode->nodeValue."<br/>";
		
		$featureList=$xpath->query(".//div[@class='product-features-list']");
		foreach($featureList as $feature){
			//echo "1"."<br/>";
			foreach($feature->getElementsByTagName('*') as $element ){
				//echo $element->nodeName;
				if($element->nodeName=='h3'){
					echo "Category:::".$element->nodeValue."<br/>";
				}else if($element->nodeName=='label'){
					echo $element->nodeValue;
				}else if($element->nodeName=='span'){
					echo ":::".$element->nodeValue."<br/>";
				}
			}
		}
		
		
		
		/*$shippingCost=returnFirstXpathObject($xpath->query(".//div[@id='LeftSummaryPanel']//div[@id='shippingSummary']//span[@id='fshippingCost']"));
		echo "Shipping Cost:::".$shippingCost->nodeValue."<br/>";
		$shippingSvc=returnFirstXpathObject($xpath->query(".//div[@id='LeftSummaryPanel']//div[@id='shippingSummary']//span[@id='fShippingSvc']"));
		echo "Shipping Services:::".$shippingSvc->nodeValue."<br/>";
		$itemLoc=returnFirstXpathObject($xpath->query(".//div[@id='LeftSummaryPanel']//div[@id='itemLocation']//div[contains(@class,'iti-eu-bld-gry')]"));
		echo "Item Location:::".$itemLoc->nodeValue."<br/>";
		$returnItem=returnFirstXpathObject($xpath->query(".//div[@id='LeftSummaryPanel']//div[contains(@class,'rpColWid')]/text()"));
		echo "Return Item:::".$returnItem->nodeValue."<br/>";
		$specTrList=$xpath->query(".//div[@class='itemAttr']//div[@class='section']//table//tr");
		$tdCnt=1;
		foreach($specTrList as $tr){
			foreach ($tr->getElementsByTagName('td') as $td){
				if($tdCnt%2==0){
				   echo ":::".$specKey=$td->nodeValue."<br/>";	
				}else{
					echo $specValue=$td->nodeValue;
				}
				$tdCnt++;
			}
			//echo $specKey.":::".$specValue."<br/>";
		}*/
		
	}else{
		echo "No More Data";
	}
}


getProductUrls(1);
?>