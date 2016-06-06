<?php 
include_once 'config/constants.php';
include_once 'includes/curl.php';
include_once 'includes/parser_functions.php';
include_once 'com/formatter/f_formatter.php';
include_once 'includes/xpath.php';
class A{
	private $numberOfCall=0;
	private $getBlankMoreThanTwoTimes=0;
	function getProductUrls($pageNo){
		echo AMAZON_XHR_URL_PART1.$pageNo.AMAZON_XHR_URL_PART2.$pageNo;
		$html=getResponseEbay(AMAZON_XHR_URL_PART1.$pageNo.AMAZON_XHR_URL_PART2.$pageNo);
		$i=0;
		//echo $html;
		if($html!=""){
			$dhtml=getDomHtml($html);
			foreach($dhtml->getElementsByTagName('div') as $div){
				if(exactAttrMatch($div, "id", "mainResults") || exactAttrMatch($div, "id", "atfResults")){
					foreach($dhtml->getElementsByTagName('div') as $dv){
						if(exactAttrMatch($dv, "class", "s-item-container")){
						
							$a=getClosestTag($dv,"a");
							if($i<11){
							echo $a->getAttribute("href")."<br/>";
							echo A::getAPid($a->getAttribute("href"))."<br/>";
							echo "*************************************************<br/><br/><br/>";
							A::getProductDetails($a->getAttribute("href"));
							echo "<br/><br/><br/>*************************************************";
							}
							$i++;
						}
					}
				}
			}
		}else{
			echo "No More Data";
		}
		return $pageNo;
	}
	function getAPid($str){
		$arr=explode("/",$str);
		$pid=$arr[5];
		return $pid;
	}
	function getProductDetails($url){
		$pid=$productTitle=$imgSrc=$specCategory=$deliveredBy="";
		$deliveryCharge=$coDelivery=$oneDayDelivery=$discountPercentage=$replacement=$comingSoon=$outofStock=$ratingVal=$noOfUser=$noOfReviews=$sellPrice=$originalPrice=0;
		$specList=array();
		$formatter=new Formatter();
		//echo $url."<br/>";
		$html=sendRequest($url);
		//echo $html;
		if($html!=""){
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$pid=A::getAPid($url);
			$pdTab=0;
			$ptitle=returnFirstXpathObject($xpath->query(".//div[contains(@id,'titleSection')]//span[contains(@id,'productTitle')]"));
			$productTitle=$ptitle->nodeValue;
			echo $productTitle."<br/>";
			foreach($dhtml->getElementsByTagName('div') as $d){
							if(exactAttrMatch($d,"id","averageCustomerReviews")){
								foreach ($d->getElementsByTagName('span') as $sp){
									if(hasAttrStartWith($sp,"class","reviewCountTextLinkedHistogram noUnderline")){
										echo "Rating:::".$sp->getAttribute("title")."<br/>";
									}
								}
								foreach ($d->getElementsByTagName('a') as $anch){
									if(exactAttrMatch($anch,"id","acrCustomerReviewLink")){
										echo "Review:::".$anch->nodeValue."<br/>";
									}
								}
							}
							$tdCntr=0;
							$trCntr=0;
							if(exactAttrMatch($d,"id","price_feature_div")){
								foreach ($d->getElementsByTagName('table') as $tbl){
									foreach($tbl->getElementsByTagName('tr') as $tr){
										$lastRow=$tr->nodeValue;   
										$trCntr++;
									}
								}
							}
							//$trCntr=0;
							if($lastRow="Inclusive of all taxes"){
								$trCntr=$trCntr-1;
							}
							$tRow=0;
							if(exactAttrMatch($d,"id","availability")){
								echo  "Availbility :::".$d->nodeValue."<br/>";
							}
							if(exactAttrMatch($d,"id","price_feature_div")){
								echo $lastRow."---".$trCntr."<br/>";
								foreach ($d->getElementsByTagName('table') as $tbl){
									foreach($tbl->getElementsByTagName('tr') as $tr){
										$tRow++;
										foreach($tr->getElementsByTagName('td') as $td){
											if($tRow<=$trCntr){
												//echo "22--".$tdCntr."<br/>";
												if($tdCntr%2==0){
													echo $td->nodeValue.":::";
												}else{
													foreach($td->getElementsByTagName('span') as $span){
														if(exactAttrMatch($span,"id","priceblock_ourprice") || exactAttrMatch($span,"id","priceblock_saleprice")){
															echo $span->nodeValue."<br/>";
														}
													}
													echo $td->nodeValue."<br/>";
												}
												$tdCntr++;
											}/*else if($trCntr>1 && $tRow==1){										
												if($tdCntr%2==0){
													echo $td->nodeValue.":::";
												}else{
													echo $td->nodeValue."<br/>";
												}
												$tdCntr++;
											}else if($trCntr>1 && $tRow==$trCntr){
												//echo "23"."<br/>";
												if($tdCntr%2==0){
													echo $td->nodeValue.":::";
												}else{
													echo $td->nodeValue."<br/>";
												}
												$tdCntr++;
											}*/
										}
									}
								}
							}
							$tdCntr=0;
							if(exactAttrMatch($d,"class","section techD")){
								foreach ($d->getElementsByTagName('table') as $tbl){
									//echo $pdTab."**&&**";
									if($pdTab==0){
										foreach($tbl->getElementsByTagName('tr') as $tr){
											foreach($tr->getElementsByTagName('td') as $td){
												if($tdCntr%2==0){
													echo $td->nodeValue.":::";
												}else{
													echo $td->nodeValue;
												}
												$tdCntr++;
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
			
		}else{
			echo "No More Data";
		}
	}
}
$a=new A();
$a->getProductUrls(1)
?>