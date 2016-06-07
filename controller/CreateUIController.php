<?php 
include_once '../dao/CreateUIDao.php';
if($_POST['type']=="get_category"){
	$pid=$_POST['pId'];
	$siteId=$_POST['siteId'];
	$createUIDaoObj = new CreateUIDao();
	echo $createUIDaoObj->getCategoryList($pid,$siteId);
}else if($_POST['type']=="get_site_list"){
	$createUIDaoObj = new CreateUIDao();
	echo $createUIDaoObj->getSiteList();
}else if($_POST['type']=="get_link"){
	$siteId=$_POST['siteId'];
	$createUIDaoObj = new CreateUIDao();
	echo $createUIDaoObj->getLink($siteId);
}
?>