<?php
function cashOnDeliveryBeautify($str){
	echo $str;
	return str_replace("Cash On Delivery ?", "",str_replace("Delivered By ?", "", $str));
}