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

class S{
	private $numberOfCall=0;
	private $getBlankMoreThanTwoTimes=0;
	function getSProductUrls($pageNo,$baseUrl,$urlPart,$siteId,$categoryId){
		$mainLink=$baseUrl."/".(($pageNo-1)*SNAPDEAL_PERPAGE_ITEM)."/".SNAPDEAL_PERPAGE_ITEM.SNAPDEAL_XHR_URL_PART2.$urlPart;
		echo $mainLink;
		$html=sendRequest($mainLink);
		$i=0;
		//echo $html;
		$GLOBALS['numberOfCall']++;
		echo "<br/><br/>--++--".$GLOBALS['numberOfCall']."--++--<br/></br/>";
		if($html!=""){
			$dhtml=getDomHtml($html);
			$xpath = new DOMXPath($dhtml);
			$anchs = $xpath->query(".//div[contains(@class,'product-image')]//a");
			foreach($anchs as $a){
					//if($i==0){
				        $saveProductDetails=new SaveProductsData();
						echo $a->getAttribute("href")."<br/>";
						echo "*************************************************<br/><br/><br/>";
						
						if($saveProductDetails->checkDataAlreadyPresent(S::getSPid($a->getAttribute("href")),$siteId)==0){
							echo "*************************************************<br/><br/><br/>";
							S::getSProductDetails($a->getAttribute("href"),$mainLink,$siteId,$categoryId);
							echo "<br/><br/><br/>*************************************************";
						}
						//getSProductDetails($a->getAttribute("href"));
						echo "<br/><br/><br/>*************************************************";
					//}
					//$i++;
			}
			$pageNo++;
			S::getSProductUrls($pageNo,$baseUrl,$urlPart,$siteId,$categoryId);
		}else{
			echo "*******No More Data";
			$GLOBALS['getBlankMoreThanTwoTimes']++;
			if($GLOBALS['getBlankMoreThanTwoTimes']<3){
				S::getSProductUrls($pageNo,$baseUrl,$urlPart,$siteId,$categoryId);
			}else{
				$GLOBALS['getBlankMoreThanTwoTimes']=0;
			}
		}
		return ((($pageNo-1)*SNAPDEAL_PERPAGE_ITEM)+$pageNo);
	}
	function getSPid($str){
		$arr=explode("/",$str);
		$pid=$arr[count($arr)-1];
		return $pid;
	}
	function getSProductDetails($url,$mainLink,$siteId,$categoryId){
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

			$pid=S::getSPid($url);
			$ptitle=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//h1[contains(@itemprop,'name')]"));
			if($ptitle){
				$productTitle=$ptitle->nodeValue;
			}
			//echo "Product Name:::".$productTitle."<br/>";
			
			$ratingDv=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-ratings')]//div"));
			if($ratingDv){
			 	$ratingVal=$ratingDv->getAttribute("ratings");
			}
			//echo "Ratings:::".$formatter->getFormattedNumberOnly($rating)."<br/>";
			
			$noOfRatingDv=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-ratings')]//div//a[contains(@class,'showRatingTooltip')]"));
			if($noOfRatingDv)
			$noOfRating=$noOfRatingDv->nodeValue;
			//echo "No of ratings:::".$formatter->getFormattedNumberOnly($noOfRating)."<br/>";
			
			$reviewSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'review-wrapper')]//a"));
			if($reviewSpn)
			$noOfReviews=$reviewSpn->nodeValue;
			//echo "Review:::".$formatter->getFormattedNumberOnly($review)."<br/>";
			
			$mrpSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-MRP-r')]"));
			if($mrpSpn)
			$originalPrice=$mrpSpn->nodeValue;
			//echo "Original Price:::".$formatter->getFormattedPrice($mrp)."<br/>";
			
			$spriceSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-PAY')]"));
			if($spriceSpn)
			$sellPrice=$spriceSpn->nodeValue;
			//echo "Selling Price:::".$formatter->getFormattedPrice($sellingPrice)."<br/>";
			
			$discountSpn=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'pdp-e-i-MRP-r')]//span[contains(@class,'pdp-e-i-MRP-r-dis')]"));
			if($discountSpn)
			$discountPercentage=$discountSpn->nodeValue;
			//echo "Discount:::".$formatter->getFormattedPrice($discount)."<br/>";
			
			
			
			$deliveryDt=returnFirstXpathObject($xpath->query(".//div[contains(@class,'comp-product-description')]//div[contains(@class,'check-avail-pin-info')]//p"));
			if($deliveryDt)
			$deliveredBy=$deliveryDt->nodeValue;
			//echo "Delivery:::".$delivery."<br/>";
			
			$soldOutDiv=returnFirstXpathObject($xpath->query(".//div[contains(@class,'soldDiscontAlert')]"));
			if($soldOutDiv){
				//echo "Not In Stock <br/>";
			}else{
				//echo "In Stock<br/>";
			}
			$replacement="7 day easy return";
			
			$trs=$xpath->query(".//div[contains(@class,'detailssubbox')]//table[contains(@class,'product-spec')]//tr");
			foreach($trs as $tr){
				foreach($tr->getElementsByTagName('th') as $th){
					$specCategory=$th->nodeValue;
				    array_push($specList,$specCategory);
				}
				$tdCntr=1;
				foreach($tr->getElementsByTagName('td') as $td){
					if($tdCntr==1){
						$specKey=$td->nodeValue;
					}else if($tdCntr==2){
						$specValue=$td->nodeValue;
					}
					if($tdCntr%2==0){
						$specList[$specKey]=$specValue;
						//echo $specKey.":::".$specValue."<br/>";
						$tdCntr=0;
					}
					$tdCntr++;
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
				$productsObj->setModel($formatter->getFormattedValues($specList['Model']));
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
			$affiliateProductsDetailsObj->setNoOfReviews($formatter->getFormattedValues($formatter->getFormattedNumberOnly($noOfReviews)));
			$affiliateProductsDetailsObj->setRatingOutOfFive($formatter->getFormattedValues($formatter->getFormattedNumberOnly($ratingVal)));
			$affiliateProductsDetailsObj->setNoOfUsers($formatter->getFormattedNumberOnly($noOfUser));
			
			$saveProductDetails->saveAffiliateProductsData($affiliateProductsDetailsObj);
			 
			
			$imgs=$xpath->query(".//ul[contains(@id,'bxsliderModal')]//li//img[contains(@class,'zoom-img-modal')]");
			foreach ($imgs as $img){
				$imgSrc=$img->getAttribute("lazysrc");
				/***************** product_image_tbl insertion *****************/
				$productImagesObj=new ProductImages();
				$productImagesObj->setImageLink($formatter->getFormattedValues($imgSrc));
				$productImagesObj->setDimension($formatter->getFormattedDimension($imgSrc));
				$productImagesObj->setImagesSavedIn($formatter->getFormattedValues(""));
				$productImagesObj->setSiteId($formatter->getFormattedNumberOnly($siteId));
				$productImagesObj->setProductId($formatter->getFormattedNumberOnly($productId));
				$saveProductDetails->saveProductImages($productImagesObj);
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
			echo "No More Data";
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
?>