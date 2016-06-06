<?php
include_once 'config/constants.php';
include_once 'includes/curl.php';
include_once 'includes/parser_functions.php';
include_once 'includes/xpath.php';
include_once 'com/formatter/f_formatter.php';
$numberOfCall=0;
$getBlankMoreThanTwoTimes=0;
function getSProductUrls($pageNo){
	echo SNAPDEAL_XHR_URL_PART1."175/".(($pageNo-1)*SNAPDEAL_PERPAGE_ITEM)."/".SNAPDEAL_PERPAGE_ITEM.SNAPDEAL_XHR_URL_PART2;
	$html=sendRequest(SNAPDEAL_XHR_URL_PART1."175/".(($pageNo-1)*SNAPDEAL_PERPAGE_ITEM)."/".SNAPDEAL_PERPAGE_ITEM.SNAPDEAL_XHR_URL_PART2);
	$i=0;
	//echo $html;
	$GLOBALS['numberOfCall']++;
	echo "<br/><br/>--++--".$GLOBALS['numberOfCall']."--++--<br/></br/>";
	if($html!=""){
		$dhtml=getDomHtml($html);
		$xpath = new DOMXPath($dhtml);
		$anchs = $xpath->query(".//div[contains(@class,'product-image')]//a");
		foreach($anchs as $a){
				if($i==0){
					echo $a->getAttribute("href")."<br/>";
					echo "*************************************************<br/><br/><br/>";
					getSProductDetails($a->getAttribute("href"));
					echo "<br/><br/><br/>*************************************************";
				}
				$i++;
		}
	}else{
		echo "No More Data";
	}
	return ((($pageNo-1)*SNAPDEAL_PERPAGE_ITEM)+$pageNo);
}

function getSProductDetails($url){
	$pid=$productTitle=$imgSrc=$specCategory=$deliveredBy="";
	$deliveryCharge=$coDelivery=$oneDayDelivery=$discountPercentage=$replacement=$comingSoon=$outofStock=$ratingVal=$noOfUser=$noOfReviews=$sellPrice=$originalPrice=0;
	$specList=array();
	//echo $url."<br/>";
	$formatter=new Formatter();
	$html=sendRequest($url);
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		$xpath = new DOMXPath($dhtml);
		$ptitle=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//h1[contains(@itemprop,'name')]"));
		$productTitle=$ptitle->nodeValue;
		echo "Product Name:::".$productTitle."<br/>";
		
		$ratingDv=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-ratings')]//div"));
		$rating=$ratingDv->getAttribute("ratings");
		echo "Ratings:::".$formatter->getFormattedNumberOnly($rating)."<br/>";
		
		$noOfRatingDv=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-ratings')]//div//a[contains(@class,'showRatingTooltip')]"));
		$noOfRating=$noOfRatingDv->nodeValue;
		echo "No of ratings:::".$formatter->getFormattedNumberOnly($noOfRating)."<br/>";
		
		$reviewSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'review-wrapper')]//a"));
		$review=$reviewSpn->nodeValue;
		echo "Review:::".$formatter->getFormattedNumberOnly($review)."<br/>";
		
		$mrpSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-MRP-r')]"));
		$mrp=$mrpSpn->nodeValue;
		echo "Original Price:::".$formatter->getFormattedPrice($mrp)."<br/>";
		
		$spriceSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-PAY')]"));
		$sellingPrice=$spriceSpn->nodeValue;
		echo "Selling Price:::".$formatter->getFormattedPrice($sellingPrice)."<br/>";
		
		$discountSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-MRP-r')]//span[contains(@class,'pdp-e-i-MRP-r-dis')]"));
		$discount=$discountSpn->nodeValue;
		echo "Discount:::".$formatter->getFormattedPrice($discount)."<br/>";
		
		$imgs=$xpath->query(".//ul[contains(@id,'bxsliderModal')]//li//img[contains(@class,'zoom-img-modal')]");
		foreach ($imgs as $img){
			echo "images:::".$img->getAttribute("lazysrc")."<br/>";
		}
		
		$deliveryDt=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'check-avail-pin-info')]//p"));
		$delivery=$deliveryDt->nodeValue;
		echo "Delivery:::".$delivery."<br/>";
		
		$soldOutDiv=returnFirstXpathObject($xpath->query(".//div[contains(@class,'soldDiscontAlert')]"));
		if($soldOutDiv){
			echo "Not In Stock <br/>";
		}else{
			echo "In Stock<br/>";
		}
		$returnDays="7 day easy return";
		
		$trs=$xpath->query(".//div[contains(@class,'detailssubbox')]//table[contains(@class,'product-spec')]//tr");
		foreach($trs as $tr){
			foreach($tr->getElementsByTagName('th') as $th){
				echo "Category:::".$th->nodeValue."<br/>";
			}
			$tdCntr=1;
			foreach($tr->getElementsByTagName('td') as $td){
				if($tdCntr==1){
					$specKey=$td->nodeValue;
				}else if($tdCntr==2){
					$specValue=$td->nodeValue;
				}
				if($tdCntr%2==0){
					echo $specKey.":::".$specValue."<br/>";
					$tdCntr=0;
				}
				$tdCntr++;
			}
		}
	}else{
		echo "No More Data";
	}
}

$start=microtime(true);
getSProductUrls(1);
$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs;
?>