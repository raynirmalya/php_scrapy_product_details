<?php 
include_once '../com/extractor/f.php';
include_once '../com/extractor/s.php';
include_once '../com/extractor/a.php';
include_once '../com/extractor/sh.php';
include_once '../com/extractor/e.php';
include_once '../com/extractor/p.php';
if(isset($_POST['requestKey']) && $_POST['requestKey']!="" ){
	//echo "fdfdfd";
	$url=$_POST['productBaseUrl'];
	$categoryId=$_POST['categoryId'];
	$categoryKey=$_POST['categoryKey'];
	$urlPart=$_POST['urlPart'];
	$siteId=$_POST['siteId'];
	$urlSecondPart=$_POST['urlSecondPart'];
	
	//echo $url.$categoryKey.$urlPart;
	if($siteId=="1"){
		$f=new F();
		$f->getFProductUrls(1,$url.$categoryKey.$urlPart,$siteId,$categoryId);
	}else if($siteId=="2"){
		$s=new S();
		$s->getSProductUrls(1,$url.$categoryKey,$urlPart,$siteId,$categoryId);
	}else if($siteId=="3"){
		$a=new A();
		$a->getAProductUrls(1,$url,$categoryKey,$siteId,$categoryId);
	}else if($siteId=="4"){
		$e=new E();
		$e->getEProductUrls(1,$url,$categoryKey,$siteId,$categoryId,$urlPart);
	}else if($siteId=="5"){
		$sh=new SH();
		$sh->getSHProductUrls(1,$url,$categoryKey,$siteId,$categoryId);
	}else if($siteId=="6"){
		echo $urlSecondPart;
		$p=new P();
		$p->getPProductUrls(1,$url,$categoryKey,$siteId,$categoryId,$urlPart,$urlSecondPart);
	}
}
?>