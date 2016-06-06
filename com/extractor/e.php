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
class E{
	private $numberOfCall=0;
	private $getBlankMoreThanTwoTimes=0;
	function getEProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId,$urlPart){
		$mainLink=$baseUrl.$categoryKey."/"."i.html?_pgn=".$pageNo;
		echo $mainLink;
		$html=sendRequest($mainLink);
		$i=0;
		$GLOBALS['numberOfCall']++;
		echo "<br/><br/>--++--".$GLOBALS['numberOfCall']."--++--<br/></br/>";
		if($html!=""){
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$anchs = $xpath->query(".//div[@id='ResultSetItems']//ul[@id='ListViewInner']//a[contains(@class,'img')]");
			foreach($anchs as $a){
				echo "test1<br/>";
				//if($i<1){
				$saveProductDetails=new SaveProductsData();
				echo "******".E::getEPid($a->getAttribute("href"))."<br/>";
				echo "----".$saveProductDetails->checkDataAlreadyPresent(E::getEPid($a->getAttribute("href")),"1")."<br/>";
				if($saveProductDetails->checkDataAlreadyPresent(E::getEPid($a->getAttribute("href")),$siteId)==0){
					echo "*************************************************<br/><br/><br/>";
					E::getEProductDetails($a->getAttribute("href"),$mainLink,$siteId,$categoryId);
					echo "<br/><br/><br/>*************************************************";
				}
				//}
				//$i++;
			}
			$pageNo++;
			E::getEProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId,$urlPart);
		}else{
			echo "*******No More Data";
			$GLOBALS['getBlankMoreThanTwoTimes']++;
			if($GLOBALS['getBlankMoreThanTwoTimes']<3){
				E::getEProductUrls($pageNo,$baseUrl,$categoryKey,$siteId,$categoryId,$urlPart);
			}else{
				$GLOBALS['getBlankMoreThanTwoTimes']=0;
			}
		}
		//return ((($pageNo-1)*FLIPKART_PERPAGE_ITEM)+$pageNo);
	}
	function getEPid($str){
		$arr=explode("?pid=",$str);
		$pid=explode("&",$arr[1])[0];
		return $pid;
	}
	function getEProductDetails($url,$mainLink,$siteId,$categoryId){
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
					
	
					//$el->nextSibling
				}
			}
		}else{
			echo "No More Data";
		}
	}
}


?>