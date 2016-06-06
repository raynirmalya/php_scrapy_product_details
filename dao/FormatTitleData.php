<?php
include_once './config/dbconfig.php';
class FormatTitleData{
	public function checkROM($str){
		if(strripos($str,"8 GB")!==false || strripos($str,"8GB")!==false){
			return "1";
		}else if(strripos($str,"16 GB")!==false || strripos($str,"16GB")!==false){
			return "2";
		}else if(strripos($str,"32 GB")!==false || strripos($str,"32GB")!==false){
			return "3";
		}else if(strripos($str,"64 GB")!==false || strripos($str,"64GB")!==false){
			return "4";
		}else{
			return "0";
		}
	
	}
	public function FormatFProductTitle(){
		$conn=Connection::make_connection();
		$codeExampleList=array();
		$romArr=array("","8GB","16GB","32GB","64GB");
		try{
			$sql="select product_id,product_title from product_tbl";
			$stmt = $conn->prepare($sql);
			$stmt->execute();
			$ress = $stmt->fetchAll();
			foreach($ress as $coll){
				/*$sql="SELECT * FROM `specification_tbl` WHERE `product_id`='".$coll['product_id']."' and `spec_key` not in ('Brand','Model Name','Model ID')";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
				$res = $stmt->fetchAll();
				$str="";*/
				$product=$coll['product_title'];
				/*$firstToWordsArr=array_slice(explode(" ",$productFull),0,2);
				//print_r($firstToWordsArr);
				$firstToWordsStr=implode(" ",$firstToWordsArr);
				$arr=array_slice(explode(" ",$productFull),2);
				$product=implode(" ",$arr);
				//echo $product;
				//$product=preg_replace("\w{1,2}/", "", $product);
				$cnt1=strlen($product);
				$rom=FormatTitleData::checkROM($productFull);*/
				$rom=FormatTitleData::checkROM($product);
				$cnt1=strlen($product);
				echo $product." :::: ";
				//$str=preg_replace('/[^A-Za-z0-9\-|\s]/', '', $str);
				//$str=preg_replace('/[+]/', '\+', $str);
				//$product=preg_replace('/('.addslashes($str).')/i',"",$product);
				//$product=preg_replace('/with(?\s+)\\d+(?\s+)GB/i',"",$product);
				$product=preg_replace('/(\s+)(HD|2G|4G|3G|Dual|Sim|GSM|CDMA|\+)/i'," ",$product);
				$product=preg_replace('/(With.*)/i',"",$product);
				$product=preg_replace('/(\(?)(\)?)/',"",$product);
	//			$product=preg_replace('/Air Force blue|Alice blue|Alizarin crimson|Almond|Amaranth|Amber|American rose|Amethyst|Android Green|Anti-flash white|Antique brass|Antique fuchsia|Antique white|Ao|Apple green|Apricot|Aquamarine|Army green|Arylide yellow|Ash grey|Asparagus|Atomic tangerine|Auburn|Aureolin|AuroMetalSaurus|Awesome|Azure|Azure mist web|Baby blue|Baby blue eyes|Baby pink|Ball Blue|Banana Mania|Banana yellow|Battleship grey|Bazaar|Beau blue|Beaver|Beige|Bisque|Bistre|Bittersweet|Black|Blanched Almond|Bleu de France|Blizzard Blue|Blond|Blue|Blue Bell|Blue Gray|Blue green|Blue purple|Blue violet|Blush|Bole|Bondi blue|Bone|Boston University Red|Bottle green|Boysenberry|Brandeis blue|Brass|Brick red|Bright cerulean|Bright green|Bright lavender|Bright maroon|Bright pink|Bright turquoise|Bright ube|Brilliant lavender|Brilliant rose|Brink pink|British racing green|Bronze|Brown|Bubble gum|Bubbles|Buff|Bulgarian rose|Burgundy|Burlywood|Burnt orange|Burnt sienna|Burnt umber|Byzantine|Byzantium|CG Blue|CG Red|Cadet|Cadet blue|Cadet grey|Cadmium green|Cadmium orange|Cadmium red|Cadmium yellow|Caf� au lait|Caf� noir|Cal Poly Pomona green|Cambridge Blue|Camel|Camouflage green|Canary|Canary yellow|Candy apple red|Candy pink|Capri|Caput mortuum|Cardinal|Caribbean green|Carmine|Carmine pink|Carmine red|Carnation pink|Carnelian|Carolina blue|Carrot orange|Celadon|Celeste|Celestial blue|Cerise|Cerise pink|Cerulean|Cerulean blue|Chamoisee|Champagne|Charcoal|Chartreuse|Cherry|Cherry blossom pink|Chestnut|Chocolate|Chrome yellow|Cinereous|Cinnabar|Cinnamon|Citrine|Classic rose|Cobalt|Cocoa brown|Coffee|Columbia blue|Cool black|Cool grey|Copper|Copper rose|Coquelicot|Coral|Coral pink|Coral red|Cordovan|Corn|Cornell Red|Cornflower|Cornflower blue|Cornsilk|Cosmic latte|Cotton candy|Cream|Crimson|Crimson Red|Crimson glory|Cyan|Daffodil|Dandelion|Dark blue|Dark brown|Dark byzantium|Dark candy apple red|Dark cerulean|Dark chestnut|Dark coral|Dark cyan|Dark electric blue|Dark goldenrod|Dark gray|Dark green|Dark jungle green|Dark khaki|Dark lava|Dark lavender|Dark magenta|Dark midnight blue|Dark olive green|Dark orange|Dark orchid|Dark pastel blue|Dark pastel green|Dark pastel purple|Dark pastel red|Dark pink|Dark powder blue|Dark raspberry|Dark red|Dark salmon|Dark scarlet|Dark sea green|Dark sienna|Dark slate blue|Dark slate gray|Dark spring green|Dark tan|Dark tangerine|Dark taupe|Dark terra cotta|Dark turquoise|Dark violet|Dartmouth green|Davy grey|Debian red|Deep carmine|Deep carmine pink|Deep carrot orange|Deep cerise|Deep champagne|Deep chestnut|Deep coffee|Deep fuchsia|Deep jungle green|Deep lilac|Deep magenta|Deep peach|Deep pink|Deep saffron|Deep sky blue|Denim|Desert|Desert sand|Dim gray|Dodger blue|Dogwood rose|Dollar bill|Drab|Duke blue|Earth yellow|Ecru|Eggplant|Eggshell|Egyptian blue|Electric blue|Electric crimson|Electric cyan|Electric green|Electric indigo|Electric lavender|Electric lime|Electric purple|Electric ultramarine|Electric violet|Electric yellow|Emerald|Eton blue|Fallow|Falu red|Famous|Fandango|Fashion fuchsia|Fawn|Feldgrau|Fern|Fern green|Ferrari Red|Field drab|Fire engine red|Firebrick|Flame|Flamingo pink|Flavescent|Flax|Floral white|Fluorescent orange|Fluorescent pink|Fluorescent yellow|Folly|Forest green|French beige|French blue|French lilac|French rose|Fuchsia|Fuchsia pink|Fulvous|Fuzzy Wuzzy|Gainsboro|Gamboge|Ghost white|Ginger|Glaucous|Glitter|Gold|Golden brown|Golden poppy|Golden yellow|Goldenrod|Granny Smith Apple|Gray|Gray asparagus|Green|Green Blue|Green yellow|Grullo|Guppie green|Halay� �be|Han blue|Han purple|Hansa yellow|Harlequin|Harvard crimson|Harvest Gold|Heart Gold|Heliotrope|Hollywood cerise|Honeydew|Hooker green|Hot magenta|Hot pink|Hunter green|Icterine|Inchworm|India green|Indian red|Indian yellow|Indigo|International Klein Blue|International orange|Iris|Isabelline|Islamic green|Ivory|Jade|Jasmine|Jasper|Jazzberry jam|Jonquil|June bud|Jungle green|KU Crimson|Kelly green|Khaki|La Salle Green|Languid lavender|Lapis lazuli|Laser Lemon|Laurel green|Lava|Lavender|Lavender blue|Lavender blush|Lavender gray|Lavender indigo|Lavender magenta|Lavender mist|Lavender pink|Lavender purple|Lavender rose|Lawn green|Lemon|Lemon Yellow|Lemon chiffon|Lemon lime|Light Crimson|Light Thulian pink|Light apricot|Light blue|Light brown|Light carmine pink|Light coral|Light cornflower blue|Light cyan|Light fuchsia pink|Light goldenrod yellow|Light gray|Light green|Light khaki|Light pastel purple|Light pink|Light salmon|Light salmon pink|Light sea green|Light sky blue|Light slate gray|Light taupe|Light yellow|Lilac|Lime|Lime green|Lincoln green|Linen|Lion|Liver|Lust|MSU Green|Macaroni and Cheese|Magenta|Magic mint|Magnolia|Mahogany|Maize|Majorelle Blue|Malachite|Manatee|Mango Tango|Mantis|Maroon|Mauve|Mauve taupe|Mauvelous|Maya blue|Meat brown|Medium Persian blue|Medium aquamarine|Medium blue|Medium candy apple red|Medium carmine|Medium champagne|Medium electric blue|Medium jungle green|Medium lavender magenta|Medium orchid|Medium purple|Medium red violet|Medium sea green|Medium slate blue|Medium spring bud|Medium spring green|Medium taupe|Medium teal blue|Medium turquoise|Medium violet red|Melon|Midnight blue|Midnight green|Mikado yellow|Mint|Mint cream|Mint green|Misty rose|Moccasin|Mode beige|Moonstone blue|Mordant red 19|Moss green|Mountain Meadow|Mountbatten pink|Mulberry|Munsell|Mustard|Myrtle|Nadeshiko pink|Napier green|Naples yellow|Navajo white|Navy blue|Neon Carrot|Neon fuchsia|Neon green|Non-photo blue|North Texas Green|Ocean Boat Blue|Ochre|Office green|Old gold|Old lace|Old lavender|Old mauve|Old rose|Olive|Olive Drab|Olive Green|Olivine|Onyx|Opera mauve|Orange|Orange Yellow|Orange peel|Orange red|Orchid|Otter brown|Outer Space|Outrageous Orange|Oxford Blue|Pacific Blue|Pakistan green|Palatinate blue|Palatinate purple|Pale aqua|Pale blue|Pale brown|Pale carmine|Pale cerulean|Pale chestnut|Pale copper|Pale cornflower blue|Pale gold|Pale goldenrod|Pale green|Pale lavender|Pale magenta|Pale pink|Pale plum|Pale red violet|Pale robin egg blue|Pale silver|Pale spring bud|Pale taupe|Pale violet red|Pansy purple|Papaya whip|Paris Green|Pastel blue|Pastel brown|Pastel gray|Pastel green|Pastel magenta|Pastel orange|Pastel pink|Pastel purple|Pastel red|Pastel violet|Pastel yellow|Patriarch|Payne grey|Peach|Peach puff|Peach yellow|Pear|Pearl|Pearl Aqua|Peridot|Periwinkle|Persian blue|Persian indigo|Persian orange|Persian pink|Persian plum|Persian red|Persian rose|Phlox|Phthalo blue|Phthalo green|Piggy pink|Pine green|Pink|Pink Flamingo|Pink Sherbet|Pink pearl|Pistachio|Platinum|Plum|Portland Orange|Powder blue|Princeton orange|Prussian blue|Psychedelic purple|Puce|Pumpkin|Purple|Purple Heart|Purple Mountains Majesty|Purple mountain majesty|Purple pizzazz|Purple taupe|Rackley|Radical Red|Raspberry|Raspberry glace|Raspberry pink|Raspberry rose|Raw Sienna|Razzle dazzle rose|Razzmatazz|Red|Red Orange|Red brown|Red violet|Rich black|Rich carmine|Rich electric blue|Rich lilac|Rich maroon|Rifle green|Robins Egg Blue|Rose|Rose bonbon|Rose ebony|Rose gold|Rose madder|Rose pink|Rose quartz|Rose taupe|Rose vale|Rosewood|Rosso corsa|Rosy brown|Royal azure|Royal blue|Royal fuchsia|Royal purple|Ruby|Ruddy|Ruddy brown|Ruddy pink|Rufous|Russet|Rust|Sacramento State green|Saddle brown|Safety orange|Saffron|Saint Patrick Blue|Salmon|Salmon pink|Sand|Sand dune|Sandstorm|Sandy brown|Sandy taupe|Sap green|Sapphire|Satin sheen gold|Scarlet|School bus yellow|Screamin Green|Sea blue|Sea green|Seal brown|Seashell|Selective yellow|Sepia|Shadow|Shamrock|Shamrock green|Shocking pink|Sienna|Silver|Sinopia|Skobeloff|Sky blue|Sky magenta|Slate blue|Slate gray|Smalt|Smokey topaz|Smoky black|Snow|Spiro Disco Ball|Spring bud|Spring green|Steel blue|Stil de grain yellow|Stizza|Stormcloud|Straw|Sunglow|Sunset|Sunset Orange|Tangelo|Tangerine|Tangerine yellow|Taupe|Taupe gray|Tawny|Tea green|Tea rose|Teal|Teal blue|Teal green|Terra cotta|Thistle|Thulian pink|Tickle Me Pink|Tiffany Blue|Tiger eye|Timberwolf|Titanium yellow|Tomato|Toolbox|Topaz|Tractor red|Trolley Grey|Tropical rain forest|True Blue|Tufts Blue|Tumbleweed|Turkish rose|Turquoise|Turquoise blue|Turquoise green|Tuscan red|Twilight lavender|Tyrian purple|UA blue|UA red|UCLA Blue|UCLA Gold|UFO Green|UP Forest green|UP Maroon|USC Cardinal|USC Gold|Ube|Ultra pink|Ultramarine|Ultramarine blue|Umber|United Nations blue|University of California Gold|Unmellow Yellow|Upsdell red|Urobilin|Utah Crimson|Vanilla|Vegas gold|Venetian red|Verdigris|Vermilion|Veronica|Violet|Violet Blue|Violet Red|Viridian|Vivid auburn|Vivid burgundy|Vivid cerise|Vivid tangerine|Vivid violet|Warm black|Waterspout|Wenge|Wheat|White|White smoke|Wild Strawberry|Wild Watermelon|Wild blue yonder|Wine|Wisteria|Xanadu|Yale Blue|Yellow|Yellow Orange|Yellow green|Zaffre|Zinnwaldite brown/i',"",$product);
				$product=preg_replace('/Maroon|Red|Orange|Yellow|Olive|Green|Purple|Fuchsia|Lime|Teal|Blue|Navy|Black|Silver|Gray|White/i'," ",$product);
				$product=preg_replace('/\\d+(\s*?)GB/i',"", $product);
				$product=preg_replace('/\s[\+]/i',"", $product);
				//echo $product;
				$finalProductTitle=trim($product);
				$cnt2=strlen($product);
				if($cnt1!=$cnt2){
					echo " changed to:::: ---".$product." ".$romArr[$rom]."<br/>";
				}else{
					echo $product." ".$romArr[$rom]."<br/>";
				}
				$sql="update product_tbl set our_product_title='".trim($finalProductTitle)."' where product_id='".$coll['product_id']."' ";
				echo $sql."<br/><br/>";
				$stmt = $conn->prepare($sql);
				$stmt->execute();
			}
		}catch (PDOException $e){
			echo $e."Database Error";
		}
	}
}

?>