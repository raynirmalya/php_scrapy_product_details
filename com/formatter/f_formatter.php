<?php
class Formatter{
	public function getFormattedValues($str){
		$str = str_replace("'", "#&34;", $str);
		$str = str_replace('"', '#&39;', $str);
		return trim($str);
	}
	public function getFormattedCOD($str){
		return Formatter::getFormattedValues(str_replace("Cash On Delivery ?", "",str_replace("Delivered By ?", "", $str)));
	}
	public function getFormattedDeliveryCharge($str){
		$str=str_replace(",", "", $str);
		$matches=array();
		preg_match('/[-+]?([0-9]*\.[0-9]+|[0-9]+)/i', $str, $matches);
		if(count($matches)>0){
			return Formatter::getFormattedValues($matches[0]);	
		}else{
			return "";
		}
	}
	public function getFormattedPrice($str){
		$str=str_replace(",", "", $str);
		$matches=array();
		preg_match('/[-+]?([0-9]*\.[0-9]+|[0-9]+)/i', $str, $matches);
		if(count($matches)>0){
			return Formatter::getFormattedValues($matches[0]);
		}else{
			return "";
		}
	}
	public function getFormattedNumberOnly($str){
		$matches=array();
		preg_match('/[-+]?([0-9]*\.[0-9]+|[0-9]+)/i', $str, $matches);
		if(count($matches)>0){
			return Formatter::getFormattedValues($matches[0]);
		}else{
			return "";
		}
	}
	public function getFormattedDimension($str){
		$matches=array();
		preg_match('/(400x400)|(1100x1100)|(75x75)|(100x100)|(275x275)|(40x40)|(200x200)|(125x125)/i', $str, $matches);
		if(count($matches)>0){
			return Formatter::getFormattedValues($matches[0]);
		}else{
			return "";
		}
	}
	public function removeBracketAfter($str){
		return preg_replace("/(\(.*)/i","",$str);
	}
}