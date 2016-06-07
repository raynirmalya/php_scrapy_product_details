<?php
class ProductImages{
	private $id;
	private $productId;
	private $siteId;
	private $imageLink;
	private $dimension;
	private $imagesSavedIn;
	
	
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getProductId() {
		return $this->productId;
	}
	public function setProductId($productId) {
		$this->productId = $productId;
	}
	public function getSiteId() {
		return $this->siteId;
	}
	public function setSiteId($siteId) {
		$this->siteId = $siteId;
	}
	public function getImageLink() {
		return $this->imageLink;
	}
	public function setImageLink($imageLink) {
		$this->imageLink = $imageLink;
	}
	public function getDimension() {
		return $this->dimension;
	}
	public function setDimension($dimension) {
		$this->dimension = $dimension;
	}
	public function getImagesSavedIn() {
		return $this->imagesSavedIn;
	}
	public function setImagesSavedIn($imagesSavedIn) {
		$this->imagesSavedIn = $imagesSavedIn;
	}
}
?>