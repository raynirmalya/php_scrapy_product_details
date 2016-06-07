<?php 

class RequestKeyGenerator{
	private $requestKey;
	private $oldRequestKey;
	function __construct(){
		if(isset($_SESSION['requestKey'])){
			$this->oldRequestKey = $_SESSION['requestKey'];
		}
	}
	private function generateRequestKey(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$uniqid = uniqid(mt_rand(), true);
		return sha1($ip.$uniqid);
	}
	public function getRequestKey(){
		$this->requestKey = $this->generateRequestKey();
		$_SESSION['requestKey'] = $this->requestKey;
		return $this->requestKey;
	}
	public function validateRequestKey(){
		if($_POST['requestKey'] == $this->oldRequestKey){
			return true;
		}else{
			return false;
		}
	}
}

?>