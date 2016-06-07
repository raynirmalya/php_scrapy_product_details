<?php

class ProductAttribute{

	private $attribute1;
	private $attribute2;
	private $attribute3;
	private $attribute4;
	private $productLink;
	
	public function getAttribute1() {
		return $this->attribute1;
	}
	public function setAttribute1($attribute1) {
		$this->attribute1 = $attribute1;
	}
	
	public function getAttribute2() {
		return $this->attribute2;
	}
	public function setAttribute2($attribute2) {
		$this->attribute2 = $attribute2;
	}
	
	public function getAttribute3() {
		return $this->attribute3;
	}
	public function setAttribute3($attribute3) {
		$this->attribute3 = $attribute3;
	}
	
	public function getAttribute4() {
		return $this->attribute4;
	}
	public function setAttribute4($attribute4) {
		$this->attribute4 = $attribute4;
	}
	
	public function getProductLink() {
		return $this->productLink;
	}
	public function setProductLink($productLink) {
		$this->productLink = $productLink;
	}
	
}	
?>