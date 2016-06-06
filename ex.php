<?php
include_once 'config/constants.php';
include_once 'includes/curl.php';
include_once 'includes/parser_functions.php';
include_once 'includes/xpath.php';
function getProductUrls($pageNo){
	$html=getResponseEbay(EBAY_XHR_URL_PART1.$pageNo);
	//echo $html;	
	$i=0;
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		$xpath = new DOMXPath($dhtml);
		$anchs = $xpath->query(".//div[@id='ResultSetItems']//h3[@class='lvtitle']/a");
		foreach ($anchs as $anchs){
			//echo $anchs->getAttribute("href")."";
			if($i<10){
				echo $anchs->getAttribute("href")."<br/>";
				echo "*************************************************<br/><br/><br/>";
				getProductDetails($anchs->getAttribute("href"));
				echo "<br/><br/><br/>*************************************************";
			}
			$i++;
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
		$ptitle=returnFirstXpathObject($xpath->query(".//h1[@id='itemTitle']/text()"));
		echo "Product Title:::".$ptitle->nodeValue."<br/>";
		$imgSrc=returnFirstXpathObject($xpath->query(".//div[@id='PicturePanel']//img[@id='icThrImg']"));
		echo "Product image:::".$imgSrc->getAttribute("src")."<br/>";		
		$itemPrice=returnFirstXpathObject($xpath->query(".//div[@id='LeftSummaryPanel']//span[@itemprop='price']"));
		echo "Item Location:::".$itemPrice->nodeValue."<br/>";
		$shippingCost=returnFirstXpathObject($xpath->query(".//div[@id='LeftSummaryPanel']//div[@id='shippingSummary']//span[@id='fshippingCost']"));
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
		}
		
	}else{
		echo "No More Data";
	}
}


getProductUrls(1);
?>