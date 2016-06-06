<?php
include_once 'config/constants.php';
include_once 'includes/curl.php';
include_once 'includes/parser_functions.php';
function getProductUrls($pageNo){
	echo EBAY_XHR_URL_PART1.$pageNo;
	$html=getResponseEbay(EBAY_XHR_URL_PART1.$pageNo);
	//echo $html;	
	$i=0;
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		foreach($dhtml->getElementsByTagName('div') as $div){
			if(exactAttrMatch($div, "id", "ResultSetItems")){
				echo "here";
				foreach($div->getElementsByTagName('h3') as $h3){
					if(exactAttrMatch($h3, "class", "lvtitle")){
						$a=getClosestTag($h3,"a");
						if($i==0){
						echo $a->getAttribute("href")."<br/>";
						echo "*************************************************<br/><br/><br/>";
						getProductDetails($a->getAttribute("href"));
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
	return ((($pageNo-1)*SNAPDEAL_PERPAGE_ITEM)+$pageNo);
}

function getProductDetails($url){
	//echo $url."<br/>";
	$html=sendRequest($url);
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		foreach($dhtml->getElementsByTagName('h1') as $h1){
			if(exactAttrMatch($h1, "id", "itemTitle")){
				echo "Product Title:::".$h1->nodeValue."<br/>";
			}
		}
		foreach($dhtml->getElementsByTagName('div') as $d){
			if(exactAttrMatch($d, "id", "PicturePanel")){
				foreach($d->getElementsByTagName('img') as $img){
					if(exactAttrMatch($img, "id", "icThrImg")){
						echo "Product image:::".$img->getAttribute("src")."<br/>";
					}
				}
			}
			if(exactAttrMatch($d, "id", "LeftSummaryPanel")){
				foreach($d->getElementsByTagName('div') as $div){
					if(exactAttrMatch($div, "id", "shippingSummary")){
						foreach ($div->getElementsByTagName('span') as $spn){
							if(exactAttrMatch($spn, "id", "fshippingCost")){
								echo "Shipping Cost:::".$spn->nodeValue."<br/>";
							}
							if(exactAttrMatch($spn, "id", "fShippingSvc")){
								echo "Shipping Service:::".$spn->nodeValue."<br/>";
							}
							
						}
					}
					if(exactAttrMatch($div, "id", "itemLocation")){
						echo "".$div->nodeValue."<br/>";
					}
				
				}
				foreach ($d->getElementsByTagName('span') as $sp){
					if(exactAttrMatch($sp, "class", "hideGspPymt")){
						$im=getClosestTag($sp,"img");
						//echo "Payment :::".$im->getAttribute("alt").$sp->nodeValue."<br/>";
					}
				}
				foreach ($d->getElementsByTagName('div') as $divv){
					if(exactAttrMatch($divv, "id", "returnsPlacementHolderId")){
						echo "Delivery:::".$divv->nextSibling."<br/>";
					}
				}
				
				//$el->nextSibling
			}
		}
	}else{
		echo "No More Data";
	}
}


getProductUrls(1);
?>