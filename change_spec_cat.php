<?php 
include_once 'config/dbconfig.php';
ini_set('max_execution_time', 10000000000);
$conn=Connection::make_connection();
//$codeExampleList=array();
//$romArr=array("","8GB","16GB","32GB","64GB");
try{
	$sql="SELECT id,`spec_category_name` FROM `specification_category_tbl` group by `spec_category_name` order by id";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$ress = $stmt->fetchAll();
	foreach($ress as $coll){
		$catId=$coll['id'];
		$sqll="SELECT st.id as id FROM specification_tbl st join specification_category_tbl sct on sct.id=st.spec_category_id and sct.spec_category_name ='".$coll['spec_category_name']."' ORDER by st.spec_category_id";
		//echo $sqll."<br/>";
		$stmtt = $conn->prepare($sqll);
		$stmtt->execute();
		$res = $stmtt->fetchAll();
		foreach($res as $col){
			$sq="update specification_tbl set spec_category_id='".$catId."' where id='".$col['id']."'";
			echo $sq."<br/>";
			$st = $conn->prepare($sq);
			$st->execute();
		}
		
	}
		
}catch (PDOException $e){
	echo $e."Database Error";
}

?>