<?php
include_once 'config/constants.php';
include_once 'includes/curl.php';
include_once 'config/dbconfig.php';
include_once 'includes/parser_functions.php';
include_once 'includes/xpath.php';
include_once 'com/formatter/f_formatter.php';
function getProductUrls($pageNo){
	//echo FLIPKART_XHR_URL."&sid=tyy%2C4io&q=mobile&start=".(($pageNo-1)*FLIPKART_PERPAGE_ITEM);
	
	$mainLink=FLIPKART_XHR_URL."&sid=tyy%2C4io&q=mobile&start=".(($pageNo-1)*FLIPKART_PERPAGE_ITEM);
	echo $mainLink;
	$html=sendRequest($mainLink);
	$i=0;
	if($html!=""){
		$dhtml=getDomHtml($html);
		$xpath = new DOMXPath($dhtml);
		$anchs = $xpath->query(".//a[@class='fk-display-block']");
		foreach($anchs as $a){
				if($i<1){
					echo FLIPKART_BASE_DOMAIN.$a->getAttribute("href")."<br/>";
					echo "*************************************************<br/><br/><br/>";
					getProductDetails(FLIPKART_BASE_DOMAIN.$a->getAttribute("href"),$mainLink);
					echo "<br/><br/><br/>*************************************************";
				}
				$i++;
		} 
	}else{
		echo "No More Data";
	}
	return ((($pageNo-1)*FLIPKART_PERPAGE_ITEM)+$pageNo);
}
function getPid($str){
	$arr=explode("?pid=",$str);
	$pid=explode("&",$arr[1])[0];
	return $pid;
}
function getProductDetails($url,$mainLink){
	$pid=$productTitle=$sellPrice=$originalPrice=$discountPercentage=$deliveryCharge=$coDelivery=$replacement=$ratingVal=$noOfUser=$noOfReviews=$imgSrc=$specCategory=$deliveredBy="";
	$comingSoon=$outofStock=false;
	$specList=array();
	$flipkartFormatter=new FlipkartFormatter();
	$html=sendRequest($url);
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		$xpath = new DOMXPath($dhtml);
		$pid=getPid($url);
		$ptitle=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'title-wrap')]//h1"));
		$productTitle=$ptitle->nodeValue;
		$sp=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[@class='price-wrap']//span[contains(@class,'selling-price')]/text()"));
		$sellPrice=$flipkartFormatter->getFormattedPrice($sp->nodeValue);
		$p=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[@class='price-wrap']//span[contains(@class,'price')]/text()"));
		$originalPrice=$flipkartFormatter->getFormattedPrice($p->nodeValue);
		$discount=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[@class='price-wrap']//span[contains(@class,'discount')]"));
		if($discount){
			$discountPercentage=$discount->nodeValue;
		}
		$dCharge=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'default-shipping-charge')]/text()"));
		if($dCharge){
			$deliveryCharge=$flipkartFormatter->getFormattedDeliveryCharge($dCharge->nodeValue);
		}
		$cSoon=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'coming-soon-status')]"));
		if($cSoon){
			$comingSoon=true;
		//echo "Coming soon:::".$cSoon->nodeValue."<br/>";
		}
		$oos=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'out-of-stock-status')]"));
		if($oos){
			$outofStock=true;
		}
		//echo "Out of stock:::".$oos->nodeValue."<br/>";
		
		$cod=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[@class='cash-on-delivery']/text()"));
		if($cod){
			$coDelivery=$flipkartFormatter->getFormattedCOD($cod->nodeValue);
		}
		$dby=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'delivery-and-cash-on-delivery-info-wrap')]//ul[@class='fk-ul-disc']//li"));
		if($dby){
			$deliveredBy=$dby->nodeValue;
			//echo "---".$deliveredBy;
		}
		$rp=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'return-policy-wrap')]//span[contains(@class,'return-text')]//b"));
		if($rp){
			$replacement=$flipkartFormatter->getFormattedNumberOnly($rp->nodeValue);
		}
		
	}else{
		echo "No More Data";
	}
}
$start = microtime(true);
getProductUrls(50);
$time_elapsed_secs = microtime(true) - $start;
echo $time_elapsed_secs;
//echo sendRequest("http://www.flipkart.com/lc/p/pv1/spotList1/spot1/sellerTable?__FK=V2b4bec9e330eaebb415214aeabdd1a12bs2e39Hj85cT8%2FPz8GZT88Pz9mS85vXrtEIJ7z5crTOrc2uo%2BWHt1pDezclR%2FekVim4ukgaxmMXEHREIbCK%2FlYOaiQd1DRoTokYepkVKRs5HDKd59JJrgCkdHw84OHuxOGLhG5FjTVMeY7a1YfMZUkuwhWZ5Z1tZivKSJgGzAgxmPvO1Cgf7QKprh%2ByWG5DzDpx8hD80Y0RikZGObIQqSWN2pDSgTDp44Hmeo74MGn%2F9YehJR886brLuMxiL0ceCQbHtokCh92tqGdfmuWOaEA8lIJBw%3D%3D&pid=MOBE6FT8DZXTBRZZ&pincode=700091&_=1442353688295");
//echo sendRequest("http://www.flipkart.com/lc/p/pv1/spotList1/spot1/shopSectionControllerView?__FK=V2b4bec9e330eaebb415214aeabdd1a12bs2e39Hj85cT8%2FPz8GZT88Pz9mS85vXrtEIJ7z5crTOrc2uo%2BWHt1pDezclR%2FekVim4ukgaxmMXEHREIbCK%2FlYOaiQd1DRoTokYepkVKRs5HDKd59JJrgCkdHw84OHuxOGLhG5FjTVMeY7a1YfMZUkuwhWZ5Z1tZivKSJgGzAgxmPvO1Cgf7QKprh%2ByWG5DzDpx8hD80Y0RikZGObIQqSWN2pDSgTDp44Hmeo74MGn%2F9YehJR886brLuMxiL0ceCQbHtokCh92tqGdfmuWOaEA8lIJBw%3D%3D&pid=MOBE6FT8DZXTBRZZ&pincode=700091&_=1442353688303");
//.010833
//echo sendRequest(FLIPKART_XHR_URL."&sid=tyy%2C4io&q=mobile&start=40");
?>