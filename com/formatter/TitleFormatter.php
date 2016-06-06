<?php
include_once './dao/FormatTitleData.php';
Class TitleFormatter{
	function formatTitleOfFProducts(){
		$formatTitleData= new FormatTitleData();
		$formatTitleData->FormatFProductTitle();
	}	
}


?>