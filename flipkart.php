<?php
include_once 'config/constants.php';
include_once 'includes/curl.php';
include_once 'includes/parser_functions.php';
function getProductUrls($pageNo){
	//echo FLIPKART_XHR_URL."&sid=tyy%2C4io&q=mobile&start=".(($pageNo-1)*FLIPKART_PERPAGE_ITEM);
	$html=sendRequest(FLIPKART_XHR_URL."&sid=tyy%2C4io&q=mobile&start=".(($pageNo-1)*FLIPKART_PERPAGE_ITEM));
	$i=0;
	if($html!=""){
		$dhtml=getDomHtml($html);
		foreach($dhtml->getElementsByTagName('a') as $a){
			if(getPosition($a, "class", "fk-display-block")===0){
				//if($i==0){
					echo FLIPKART_BASE_DOMAIN.$a->getAttribute("href")."<br/>";
					echo "*************************************************<br/><br/><br/>";
					getProductDetails(FLIPKART_BASE_DOMAIN.$a->getAttribute("href"));
					echo "<br/><br/><br/>*************************************************";
				//}
				//$i++;
			}	
		} 
	}else{
		echo "No More Data";
	}
	return ((($pageNo-1)*FLIPKART_PERPAGE_ITEM)+$pageNo);
}
function getProductDetails($url){
	//echo $url."<br/>";
	$html=sendRequest($url);
	//echo $html;
	if($html!=""){
		$dhtml=getDomHtml($html);
		foreach($dhtml->getElementsByTagName('div') as $div){
			if(hasAttrStartWith($div,"class","title-wrap")){
				echo "Title:::".$div->nodeValue."<br/>";
			}	
			if(hasAttrStartWith($div,"class","ratingHistogram")){
				foreach($div->getElementsByTagName('div') as $diiv){
					if(hasAttrStartWith($diiv,"class","bigStar")){
						echo "Rating:::".$diiv->nodeValue."<br/>";
					}
				}
				foreach($div->getElementsByTagName('p') as $p){
					if(hasAttrStartWith($p,"class","subText") && $p->nodeValue !="Average Rating"){
						echo "Rating by no of users:::".$p->nodeValue."<br/>";
					}
				}
			}	
			if(hasAttrStartWith($div,"class","helpfulReviews")){
				$obj=getClosestTag($div,"a");
				echo "No of reviews:::".$obj->nodeValue."<br/>";
			}
			if(hasAttrStartWith($div,"class","imgWrapper")){
				foreach($div->getElementsByTagName('img') as $imgs){
					if(hasAttrStartWith($imgs,"class","productImage")){
						echo "Image Links:::".$imgs->getAttribute("data-src")."<br/>";
					}
				}
			}
			if(hasAttrStartWith($div,"class","price-wrap")){
				foreach($div->getElementsByTagName('span') as $sp){
					if(hasAttrStartWith($sp,"class","selling-price")){
						echo "Price:::".$sp->nodeValue."<br/>";
					}
				}
			}
			if(hasAttrStartWith($div,"class","default-shipping-charge")){
				echo "Delivery Charge:::".$div->nodeValue."<br/>";
			}
			$offer=0;
			if(hasAttrStartWith($div,"class","offers")){
				foreach($div->getElementsByTagName('li') as $li){
					foreach($li->getElementsByTagName('span') as $span){
						if(hasAttrStartWith($span,"class","offer-text")){
							echo "Offers:::".$span->nodeValue."<br/>";
						}
					}
					$offer++;
				}
				echo "No of Offers:::".$offer."<br/>";
			}			
			$liCnt=0;
			if(hasAttrStartWith($div,"class","delivery-and-cash-on-delivery-info-wrap")){
				foreach($div->getElementsByTagName('ul') as $ul){
					if(hasAttrStartWith($ul,"class","fk-ul-disc")){
						foreach($ul->getElementsByTagName('li') as $lii){
							if($liCnt==0){
								echo "Delivered By:::".$lii->nodeValue."<br/>";
							}else if($liCnt==1){
								foreach($lii->getElementsByTagName('div') as $dv){
									if(hasAttrStartWith($dv,"class","express")){
										echo "Express Delivery:::"."Available"."<br/>";
									}
								}
								echo "Express Delivered By:::".$lii->nodeValue."<br/>";
							}
							$liCnt++;
						}
					}
				}
			}
			if(exactAttrMatch($div,"class","cash-on-delivery")){
				echo "Cash on delivery:::".$div->nodeValue."<br/>";
			}
			if(hasAttrStartWith($div,"class","return-policy-wrap")){
				foreach($div->getElementsByTagName('span') as $sspn){
					if(hasAttrStartWith($sspn,"class","return-text")){
						foreach($sspn->getElementsByTagName('b') as $b){
							echo "Replacement:::".$b->nodeValue."<br/>";
						}
					}					
				}
			}
			if(hasAttrStartWith($div,"class","productSpecs")){
				foreach($div->getElementsByTagName('table') as $tbl){
					if(hasAttrStartWith($tbl,"class","specTable")){
						foreach($tbl->getElementsByTagName('tr') as $tr){
							foreach($tr->getElementsByTagName('th') as $th){
								if(hasAttrStartWith($th,"class","groupHead")){
									echo "Category:::".$th->nodeValue."<br/>";
								}
							}
							$specsKey="";
							$specsValue="";
							$specCnt=0;
							foreach($tr->getElementsByTagName('td') as $td){
								$specCnt++;
								if(hasAttrStartWith($td,"class","specsKey")){
									$specsKey=$td->nodeValue;
								}
								if(hasAttrStartWith($td,"class","specsValue")){
									$specsValue=$td->nodeValue;
								}
								if($specCnt%2==0){
									echo $specsKey.":::".$specsValue."<br/>";
								}
							}
						}
					}
					
				}
			}
		}
	}else{
		echo "No More Data";
	}
}
getProductUrls(1);
//echo sendRequest("http://www.flipkart.com/lc/p/pv1/spotList1/spot1/sellerTable?__FK=V2b4bec9e330eaebb415214aeabdd1a12bs2e39Hj85cT8%2FPz8GZT88Pz9mS85vXrtEIJ7z5crTOrc2uo%2BWHt1pDezclR%2FekVim4ukgaxmMXEHREIbCK%2FlYOaiQd1DRoTokYepkVKRs5HDKd59JJrgCkdHw84OHuxOGLhG5FjTVMeY7a1YfMZUkuwhWZ5Z1tZivKSJgGzAgxmPvO1Cgf7QKprh%2ByWG5DzDpx8hD80Y0RikZGObIQqSWN2pDSgTDp44Hmeo74MGn%2F9YehJR886brLuMxiL0ceCQbHtokCh92tqGdfmuWOaEA8lIJBw%3D%3D&pid=MOBE6FT8DZXTBRZZ&pincode=700091&_=1442353688295");
//echo sendRequest("http://www.flipkart.com/lc/p/pv1/spotList1/spot1/shopSectionControllerView?__FK=V2b4bec9e330eaebb415214aeabdd1a12bs2e39Hj85cT8%2FPz8GZT88Pz9mS85vXrtEIJ7z5crTOrc2uo%2BWHt1pDezclR%2FekVim4ukgaxmMXEHREIbCK%2FlYOaiQd1DRoTokYepkVKRs5HDKd59JJrgCkdHw84OHuxOGLhG5FjTVMeY7a1YfMZUkuwhWZ5Z1tZivKSJgGzAgxmPvO1Cgf7QKprh%2ByWG5DzDpx8hD80Y0RikZGObIQqSWN2pDSgTDp44Hmeo74MGn%2F9YehJR886brLuMxiL0ceCQbHtokCh92tqGdfmuWOaEA8lIJBw%3D%3D&pid=MOBE6FT8DZXTBRZZ&pincode=700091&_=1442353688303");
//.010833
//echo sendRequest(FLIPKART_XHR_URL."&sid=tyy%2C4io&q=mobile&start=40");
?>