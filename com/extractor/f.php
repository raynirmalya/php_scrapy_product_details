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

class F{
	
	private $numberOfCall=0;
	private $getBlankMoreThanTwoTimes=0;
	function getFProductUrls($pageNo,$baseUrl,$siteId,$categoryId){
		$mainLink=$baseUrl."&start=".(($pageNo-1)*FLIPKART_PERPAGE_ITEM);
		echo $mainLink;
		$html=sendRequest($mainLink);
		$i=0;
		$GLOBALS['numberOfCall']++;
		echo "<br/><br/>--++--".$GLOBALS['numberOfCall']."--++--<br/></br/>";
		if($html!=""){
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$anchs = $xpath->query(".//a[@class='fk-display-block']");
			foreach($anchs as $a){
					//if($i<1){
						$saveProductDetails=new SaveProductsData();  
						echo "******".F::getFPid(FLIPKART_BASE_DOMAIN.$a->getAttribute("href"))."<br/>";
						echo "----".$saveProductDetails->checkDataAlreadyPresent(F::getFPid(FLIPKART_BASE_DOMAIN.$a->getAttribute("href")),"1")."<br/>";
						if($saveProductDetails->checkDataAlreadyPresent(F::getFPid(FLIPKART_BASE_DOMAIN.$a->getAttribute("href")),$siteId)==0){
							echo "*************************************************<br/><br/><br/>";
							F::getFProductDetails(FLIPKART_BASE_DOMAIN.$a->getAttribute("href"),$mainLink,$siteId,$categoryId);
							echo "<br/><br/><br/>*************************************************";
						}
					//}
					//$i++;
			} 
			$pageNo++;
			F::getFProductUrls($pageNo,$baseUrl,$siteId,$categoryId);
		}else{
			echo "*******No More Data";
			$GLOBALS['getBlankMoreThanTwoTimes']++;
			if($GLOBALS['getBlankMoreThanTwoTimes']<3){
				F::getFProductUrls($pageNo,$baseUrl,$siteId,$categoryId);
			}else{
				$GLOBALS['getBlankMoreThanTwoTimes']=0;
			}
		}
		//return ((($pageNo-1)*FLIPKART_PERPAGE_ITEM)+$pageNo);
	}
	function getFPid($str){
		$arr=explode("?pid=",$str);
		$pid=explode("&",$arr[1])[0];
		return $pid;
	}
	function getFProductDetails($url,$mainLink,$siteId,$categoryId){
		$pid=$productTitle=$imgSrc=$specCategory=$deliveredBy="";
		$deliveryCharge=$coDelivery=$oneDayDelivery=$discountPercentage=$replacement=$comingSoon=$outofStock=$ratingVal=$noOfUser=$noOfReviews=$sellPrice=$originalPrice=0;
		$specList=array();
		$formatter=new Formatter();
		$html=sendRequest($url);
		//echo $html;
		if($html!=""){
			echo "<br/><br/>INSIDE PRODUCT URL STARTS<br/><br/>";
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$pid=F::getFPid($url);
			$ptitle=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'title-wrap')]//h1"));
			$productTitle=$ptitle->nodeValue;
			$sp=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'price-wrap')]//span[contains(@class,'selling-price')]/text()"));
			if($formatter->getFormattedPrice($sp->nodeValue)!="")
				$sellPrice=$formatter->getFormattedPrice($sp->nodeValue);
			$p=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'price-wrap')]//span[contains(@class,'price')]/text()"));
			if($formatter->getFormattedPrice($p->nodeValue)!="")
				$originalPrice=$formatter->getFormattedPrice($p->nodeValue);
			$discount=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'price-wrap')]//span[contains(@class,'discount')]"));
			if($discount){
				if($formatter->getFormattedNumberOnly($discount->nodeValue)!==""){
					$discountPercentage=$formatter->getFormattedNumberOnly($discount->nodeValue);
				}
			}
			$dCharge=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'default-shipping-charge')]/text()"));
			if($dCharge){
				if($formatter->getFormattedDeliveryCharge($dCharge->nodeValue)!=""){
					$deliveryCharge=$formatter->getFormattedDeliveryCharge($dCharge->nodeValue);
				}
			}
			$cSoon=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'coming-soon-status')]"));
			if($cSoon){
				$comingSoon=1;
			}
			$oos=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'out-of-stock-status')]"));
			if($oos){
				$outofStock=1;
			}
			$cod=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'cash-on-delivery')]/text()"));
			if($cod){
				if($formatter->getFormattedCOD($cod->nodeValue)!=""){
					$coDelivery=$formatter->getFormattedCOD($cod->nodeValue);
				}
			}
			$dby=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'delivery-and-cash-on-delivery-info-wrap')]//ul[contains(@class,'fk-ul-disc')]//li"));
			if($dby){
				$deliveredBy=$dby->nodeValue;
			}
			$rp=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'return-policy-wrap')]//span[contains(@class,'return-text')]//b"));
			if($rp){
				if($formatter->getFormattedNumberOnly($rp->nodeValue)!=""){
					$replacement=$formatter->getFormattedNumberOnly($rp->nodeValue);
				}
			}
			$expd=returnFirstXpathObject($xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'delivery-and-cash-on-delivery-info-wrap')]//ul[contains(@class,'fk-ul-disc')]"));
			$licnt=0;
			if($expd){
					foreach($expd as $lii){
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
			$rating=returnFirstXpathObject($xpath->query(".//div[contains(@class,'ratingHistogram')]//div[contains(@class,'bigStar')]/text()"));	
			if($rating){
				if($formatter->getFormattedNumberOnly($rating->nodeValue)!=""){
					$ratingVal=$formatter->getFormattedNumberOnly($rating->nodeValue);
				}
			}
			$review=$xpath->query(".//div[contains(@class,'ratingHistogram')]//p[contains(@class,'subText')]");
			if($review){
				foreach($review as $p){
					if(hasAttrStartWith($p,"class","subText") && $p->nodeValue !="Average Rating" && $formatter->getFormattedNumberOnly($p->nodeValue)!=""){
						$noOfUser=$formatter->getFormattedNumberOnly($p->nodeValue);
					}
				}
			}
			$nreview=$xpath->query(".//div[contains(@class,'helpfulReviews')]//a[contains(@class,'lnkViewAll')]/text()");
			if($nreview && ($nreview->length)>0){
				if($formatter->getFormattedNumberOnly($nreview->item(0)->nodeValue)!=""){
					$noOfReviews=$formatter->getFormattedNumberOnly($nreview->item(0)->nodeValue);
				}
			}else{
				$nreview=$xpath->query(".//div[contains(@class,'review bigReview')]");
				$noOfReviews=$nreview->length;
			}
			
			
			$prodSpecs=$xpath->query(".//div[contains(@class,'productSpecs')]//table[contains(@class,'specTable')]//tr");
			foreach($prodSpecs as $tr){
				foreach($tr->getElementsByTagName('th') as $th){
					if(hasAttrStartWith($th,"class","groupHead")){
						$specCategory=$th->nodeValue;
						array_push($specList,$specCategory);
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
						$specList[$specsKey]=$specsValue;
					}
				}
			}
			
			$saveProductDetails=new SaveProductsData();
			$fetchCategotyDetails=new FetchCategoryData();
			
			$titleSpecs=$fetchCategotyDetails->getTitleSpecs($categoryId,$siteId);
			$attr1=$formatter->removeBracketAfter($formatter->getFormattedValues($specList[$titleSpecs[0]]));
			$attr2=$formatter->removeBracketAfter($formatter->getFormattedValues($specList[$titleSpecs[1]]));
			$attr3=$formatter->removeBracketAfter($formatter->getFormattedValues($specList[$titleSpecs[2]]));
			$attr4=$formatter->removeBracketAfter($formatter->getFormattedValues($specList[$titleSpecs[3]]));
			
			
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
		    	$productsObj->setBrand($formatter->getFormattedValues($specList["Brand"]));
		    	$productsObj->setModel($formatter->getFormattedValues($specList['Model Name']));
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
		    $affiliateProductsDetailsObj->setAttribute4($attr3);
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
	
			$olist=$xpath->query(".//div[contains(@class,'product-details')]//div[contains(@class,'offers')]//ul//li[contains(@class,'offer')]//span[contains(@class,'offer-text')]");
			$o=0;
			if($olist){
				foreach ($olist as $offer){
					/***************** offers_tbl insertion *****************/
					$offersObj=new Offers();
					$offersObj->setOfferDetails($formatter->getFormattedValues($offer->nodeValue));
					$offersObj->setSiteId($formatter->getFormattedValues($siteId));
					$offersObj->setProductId($formatter->getFormattedValues($productId));
					$saveProductDetails->saveProductOffers($offersObj);
				}
			}
			$imgs=$xpath->query(".//div[contains(@class,'imgWrapper')]//img[contains(@class,'productImage')]");
			if($imgs){
				foreach ($imgs as $img){
					$imgSrc=$img->getAttribute("data-src");
					/***************** product_image_tbl insertion *****************/
					$productImagesObj=new ProductImages();				
					$productImagesObj->setImageLink($formatter->getFormattedValues($imgSrc));
					$productImagesObj->setDimension($formatter->getFormattedDimension($imgSrc));
					$productImagesObj->setImagesSavedIn($formatter->getFormattedValues(""));
					$productImagesObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
					$productImagesObj->setProductId($formatter->getFormattedNumberOnly($productId));
					$saveProductDetails->saveProductImages($productImagesObj);
				}
			}
			$cid="";
			foreach ($specList as $key=>$val){
				if(is_int($key)){
					/***************** specification_category_tbl insertion *****************/
					$specificationCategoriesObj=new SpecificationCategories();
					$specificationCategoriesObj->setSpecCategoryName($formatter->getFormattedValues($val));
					$specificationCategoriesObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
					$specificationCategoriesObj->setProductCategoryId($categoryId);
					$cid=$saveProductDetails->saveSpecificationCategories($specificationCategoriesObj);
				}else{
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
			echo "No More Data 12121";
		}
	}
	
	function formatTitle($product,$attribute){
		$product=preg_replace('/(\s+)(HD|2G|4G|3G|Dual|Sim|WCDMA|Full|GSM|CDMA|for|any|mobile|Card|\+)/i'," ",$product);
		$product=preg_replace('/(With.*)/i',"",$product);
		$product=preg_replace('/(\(?)(\)?)/',"",$product);
		
		//			$product=preg_replace('/Air Force blue|Alice blue|Alizarin crimson|Almond|Amaranth|Amber|American rose|Amethyst|Android Green|Anti-flash white|Antique brass|Antique fuchsia|Antique white|Ao|Apple green|Apricot|Aquamarine|Army green|Arylide yellow|Ash grey|Asparagus|Atomic tangerine|Auburn|Aureolin|AuroMetalSaurus|Awesome|Azure|Azure mist web|Baby blue|Baby blue eyes|Baby pink|Ball Blue|Banana Mania|Banana yellow|Battleship grey|Bazaar|Beau blue|Beaver|Beige|Bisque|Bistre|Bittersweet|Black|Blanched Almond|Bleu de France|Blizzard Blue|Blond|Blue|Blue Bell|Blue Gray|Blue green|Blue purple|Blue violet|Blush|Bole|Bondi blue|Bone|Boston University Red|Bottle green|Boysenberry|Brandeis blue|Brass|Brick red|Bright cerulean|Bright green|Bright lavender|Bright maroon|Bright pink|Bright turquoise|Bright ube|Brilliant lavender|Brilliant rose|Brink pink|British racing green|Bronze|Brown|Bubble gum|Bubbles|Buff|Bulgarian rose|Burgundy|Burlywood|Burnt orange|Burnt sienna|Burnt umber|Byzantine|Byzantium|CG Blue|CG Red|Cadet|Cadet blue|Cadet grey|Cadmium green|Cadmium orange|Cadmium red|Cadmium yellow|Café au lait|Café noir|Cal Poly Pomona green|Cambridge Blue|Camel|Camouflage green|Canary|Canary yellow|Candy apple red|Candy pink|Capri|Caput mortuum|Cardinal|Caribbean green|Carmine|Carmine pink|Carmine red|Carnation pink|Carnelian|Carolina blue|Carrot orange|Celadon|Celeste|Celestial blue|Cerise|Cerise pink|Cerulean|Cerulean blue|Chamoisee|Champagne|Charcoal|Chartreuse|Cherry|Cherry blossom pink|Chestnut|Chocolate|Chrome yellow|Cinereous|Cinnabar|Cinnamon|Citrine|Classic rose|Cobalt|Cocoa brown|Coffee|Columbia blue|Cool black|Cool grey|Copper|Copper rose|Coquelicot|Coral|Coral pink|Coral red|Cordovan|Corn|Cornell Red|Cornflower|Cornflower blue|Cornsilk|Cosmic latte|Cotton candy|Cream|Crimson|Crimson Red|Crimson glory|Cyan|Daffodil|Dandelion|Dark blue|Dark brown|Dark byzantium|Dark candy apple red|Dark cerulean|Dark chestnut|Dark coral|Dark cyan|Dark electric blue|Dark goldenrod|Dark gray|Dark green|Dark jungle green|Dark khaki|Dark lava|Dark lavender|Dark magenta|Dark midnight blue|Dark olive green|Dark orange|Dark orchid|Dark pastel blue|Dark pastel green|Dark pastel purple|Dark pastel red|Dark pink|Dark powder blue|Dark raspberry|Dark red|Dark salmon|Dark scarlet|Dark sea green|Dark sienna|Dark slate blue|Dark slate gray|Dark spring green|Dark tan|Dark tangerine|Dark taupe|Dark terra cotta|Dark turquoise|Dark violet|Dartmouth green|Davy grey|Debian red|Deep carmine|Deep carmine pink|Deep carrot orange|Deep cerise|Deep champagne|Deep chestnut|Deep coffee|Deep fuchsia|Deep jungle green|Deep lilac|Deep magenta|Deep peach|Deep pink|Deep saffron|Deep sky blue|Denim|Desert|Desert sand|Dim gray|Dodger blue|Dogwood rose|Dollar bill|Drab|Duke blue|Earth yellow|Ecru|Eggplant|Eggshell|Egyptian blue|Electric blue|Electric crimson|Electric cyan|Electric green|Electric indigo|Electric lavender|Electric lime|Electric purple|Electric ultramarine|Electric violet|Electric yellow|Emerald|Eton blue|Fallow|Falu red|Famous|Fandango|Fashion fuchsia|Fawn|Feldgrau|Fern|Fern green|Ferrari Red|Field drab|Fire engine red|Firebrick|Flame|Flamingo pink|Flavescent|Flax|Floral white|Fluorescent orange|Fluorescent pink|Fluorescent yellow|Folly|Forest green|French beige|French blue|French lilac|French rose|Fuchsia|Fuchsia pink|Fulvous|Fuzzy Wuzzy|Gainsboro|Gamboge|Ghost white|Ginger|Glaucous|Glitter|Gold|Golden brown|Golden poppy|Golden yellow|Goldenrod|Granny Smith Apple|Gray|Gray asparagus|Green|Green Blue|Green yellow|Grullo|Guppie green|Halayà úbe|Han blue|Han purple|Hansa yellow|Harlequin|Harvard crimson|Harvest Gold|Heart Gold|Heliotrope|Hollywood cerise|Honeydew|Hooker green|Hot magenta|Hot pink|Hunter green|Icterine|Inchworm|India green|Indian red|Indian yellow|Indigo|International Klein Blue|International orange|Iris|Isabelline|Islamic green|Ivory|Jade|Jasmine|Jasper|Jazzberry jam|Jonquil|June bud|Jungle green|KU Crimson|Kelly green|Khaki|La Salle Green|Languid lavender|Lapis lazuli|Laser Lemon|Laurel green|Lava|Lavender|Lavender blue|Lavender blush|Lavender gray|Lavender indigo|Lavender magenta|Lavender mist|Lavender pink|Lavender purple|Lavender rose|Lawn green|Lemon|Lemon Yellow|Lemon chiffon|Lemon lime|Light Crimson|Light Thulian pink|Light apricot|Light blue|Light brown|Light carmine pink|Light coral|Light cornflower blue|Light cyan|Light fuchsia pink|Light goldenrod yellow|Light gray|Light green|Light khaki|Light pastel purple|Light pink|Light salmon|Light salmon pink|Light sea green|Light sky blue|Light slate gray|Light taupe|Light yellow|Lilac|Lime|Lime green|Lincoln green|Linen|Lion|Liver|Lust|MSU Green|Macaroni and Cheese|Magenta|Magic mint|Magnolia|Mahogany|Maize|Majorelle Blue|Malachite|Manatee|Mango Tango|Mantis|Maroon|Mauve|Mauve taupe|Mauvelous|Maya blue|Meat brown|Medium Persian blue|Medium aquamarine|Medium blue|Medium candy apple red|Medium carmine|Medium champagne|Medium electric blue|Medium jungle green|Medium lavender magenta|Medium orchid|Medium purple|Medium red violet|Medium sea green|Medium slate blue|Medium spring bud|Medium spring green|Medium taupe|Medium teal blue|Medium turquoise|Medium violet red|Melon|Midnight blue|Midnight green|Mikado yellow|Mint|Mint cream|Mint green|Misty rose|Moccasin|Mode beige|Moonstone blue|Mordant red 19|Moss green|Mountain Meadow|Mountbatten pink|Mulberry|Munsell|Mustard|Myrtle|Nadeshiko pink|Napier green|Naples yellow|Navajo white|Navy blue|Neon Carrot|Neon fuchsia|Neon green|Non-photo blue|North Texas Green|Ocean Boat Blue|Ochre|Office green|Old gold|Old lace|Old lavender|Old mauve|Old rose|Olive|Olive Drab|Olive Green|Olivine|Onyx|Opera mauve|Orange|Orange Yellow|Orange peel|Orange red|Orchid|Otter brown|Outer Space|Outrageous Orange|Oxford Blue|Pacific Blue|Pakistan green|Palatinate blue|Palatinate purple|Pale aqua|Pale blue|Pale brown|Pale carmine|Pale cerulean|Pale chestnut|Pale copper|Pale cornflower blue|Pale gold|Pale goldenrod|Pale green|Pale lavender|Pale magenta|Pale pink|Pale plum|Pale red violet|Pale robin egg blue|Pale silver|Pale spring bud|Pale taupe|Pale violet red|Pansy purple|Papaya whip|Paris Green|Pastel blue|Pastel brown|Pastel gray|Pastel green|Pastel magenta|Pastel orange|Pastel pink|Pastel purple|Pastel red|Pastel violet|Pastel yellow|Patriarch|Payne grey|Peach|Peach puff|Peach yellow|Pear|Pearl|Pearl Aqua|Peridot|Periwinkle|Persian blue|Persian indigo|Persian orange|Persian pink|Persian plum|Persian red|Persian rose|Phlox|Phthalo blue|Phthalo green|Piggy pink|Pine green|Pink|Pink Flamingo|Pink Sherbet|Pink pearl|Pistachio|Platinum|Plum|Portland Orange|Powder blue|Princeton orange|Prussian blue|Psychedelic purple|Puce|Pumpkin|Purple|Purple Heart|Purple Mountains Majesty|Purple mountain majesty|Purple pizzazz|Purple taupe|Rackley|Radical Red|Raspberry|Raspberry glace|Raspberry pink|Raspberry rose|Raw Sienna|Razzle dazzle rose|Razzmatazz|Red|Red Orange|Red brown|Red violet|Rich black|Rich carmine|Rich electric blue|Rich lilac|Rich maroon|Rifle green|Robins Egg Blue|Rose|Rose bonbon|Rose ebony|Rose gold|Rose madder|Rose pink|Rose quartz|Rose taupe|Rose vale|Rosewood|Rosso corsa|Rosy brown|Royal azure|Royal blue|Royal fuchsia|Royal purple|Ruby|Ruddy|Ruddy brown|Ruddy pink|Rufous|Russet|Rust|Sacramento State green|Saddle brown|Safety orange|Saffron|Saint Patrick Blue|Salmon|Salmon pink|Sand|Sand dune|Sandstorm|Sandy brown|Sandy taupe|Sap green|Sapphire|Satin sheen gold|Scarlet|School bus yellow|Screamin Green|Sea blue|Sea green|Seal brown|Seashell|Selective yellow|Sepia|Shadow|Shamrock|Shamrock green|Shocking pink|Sienna|Silver|Sinopia|Skobeloff|Sky blue|Sky magenta|Slate blue|Slate gray|Smalt|Smokey topaz|Smoky black|Snow|Spiro Disco Ball|Spring bud|Spring green|Steel blue|Stil de grain yellow|Stizza|Stormcloud|Straw|Sunglow|Sunset|Sunset Orange|Tangelo|Tangerine|Tangerine yellow|Taupe|Taupe gray|Tawny|Tea green|Tea rose|Teal|Teal blue|Teal green|Terra cotta|Thistle|Thulian pink|Tickle Me Pink|Tiffany Blue|Tiger eye|Timberwolf|Titanium yellow|Tomato|Toolbox|Topaz|Tractor red|Trolley Grey|Tropical rain forest|True Blue|Tufts Blue|Tumbleweed|Turkish rose|Turquoise|Turquoise blue|Turquoise green|Tuscan red|Twilight lavender|Tyrian purple|UA blue|UA red|UCLA Blue|UCLA Gold|UFO Green|UP Forest green|UP Maroon|USC Cardinal|USC Gold|Ube|Ultra pink|Ultramarine|Ultramarine blue|Umber|United Nations blue|University of California Gold|Unmellow Yellow|Upsdell red|Urobilin|Utah Crimson|Vanilla|Vegas gold|Venetian red|Verdigris|Vermilion|Veronica|Violet|Violet Blue|Violet Red|Viridian|Vivid auburn|Vivid burgundy|Vivid cerise|Vivid tangerine|Vivid violet|Warm black|Waterspout|Wenge|Wheat|White|White smoke|Wild Strawberry|Wild Watermelon|Wild blue yonder|Wine|Wisteria|Xanadu|Yale Blue|Yellow|Yellow Orange|Yellow green|Zaffre|Zinnwaldite brown/i',"",$product);
		$product=preg_replace('/Maroon|Red|Orange|Yellow|Olive|Green|Purple|Fuchsia|Lime|Teal|Blue|Navy|Black|Silver|Gray|White/i'," ",$product);
		if($attribute!="")
			$product=preg_replace('/'.$attribute.'/i'," ",$product);
		$product=preg_replace('/\\d+(\s*?)GB/i',"", $product);
		$product=preg_replace('/\\d+(\s*?)mAh/i',"", $product);
		$product=preg_replace('/\\d+(\s*?)MP/i',"", $product);
		$product=preg_replace('/\\d+(\s*?)GHz/i',"", $product);
		$product=preg_replace('/\\d+(\s*?)inch/i',"", $product);
		$product=preg_replace('/\s[\+]/i',"", $product);
		$product=preg_replace('/[^a-zA-Z0-9\s]/i', '', $product);
		//echo $product;
		return $finalProductTitle=trim($product);
	}

}
//getFProductUrls(1);
//echo sendRequest("http://www.flipkart.com/lc/p/pv1/spotList1/spot1/sellerTable?__FK=V2b4bec9e330eaebb415214aeabdd1a12bs2e39Hj85cT8%2FPz8GZT88Pz9mS85vXrtEIJ7z5crTOrc2uo%2BWHt1pDezclR%2FekVim4ukgaxmMXEHREIbCK%2FlYOaiQd1DRoTokYepkVKRs5HDKd59JJrgCkdHw84OHuxOGLhG5FjTVMeY7a1YfMZUkuwhWZ5Z1tZivKSJgGzAgxmPvO1Cgf7QKprh%2ByWG5DzDpx8hD80Y0RikZGObIQqSWN2pDSgTDp44Hmeo74MGn%2F9YehJR886brLuMxiL0ceCQbHtokCh92tqGdfmuWOaEA8lIJBw%3D%3D&pid=MOBE6FT8DZXTBRZZ&pincode=700091&_=1442353688295");
//echo sendRequest("http://www.flipkart.com/lc/p/pv1/spotList1/spot1/shopSectionControllerView?__FK=V2b4bec9e330eaebb415214aeabdd1a12bs2e39Hj85cT8%2FPz8GZT88Pz9mS85vXrtEIJ7z5crTOrc2uo%2BWHt1pDezclR%2FekVim4ukgaxmMXEHREIbCK%2FlYOaiQd1DRoTokYepkVKRs5HDKd59JJrgCkdHw84OHuxOGLhG5FjTVMeY7a1YfMZUkuwhWZ5Z1tZivKSJgGzAgxmPvO1Cgf7QKprh%2ByWG5DzDpx8hD80Y0RikZGObIQqSWN2pDSgTDp44Hmeo74MGn%2F9YehJR886brLuMxiL0ceCQbHtokCh92tqGdfmuWOaEA8lIJBw%3D%3D&pid=MOBE6FT8DZXTBRZZ&pincode=700091&_=1442353688303");
//.010833
//echo sendRequest(FLIPKART_XHR_URL."&sid=tyy%2C4io&q=mobile&start=40");
?>