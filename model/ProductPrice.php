<?php
class ProductPrice{
	private $id;
	private $productId;
	private $siteId;
	private $sellingPrice;
	private $originalPrice;
	private $discountInPercentage;


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
	public function getSellingPrice() {
		return $this->sellingPrice;
	}
	public function setSellingPrice($sellingPrice) {
		$this->sellingPrice = $sellingPrice;
	}
	public function getOriginalPrice() {
		return $this->originalPrice;
	}
	public function setOriginalPrice($originalPrice) {
		$this->originalPrice = $originalPrice;
	}
	public function getDiscountInPercentage() {
		return $this->discountInPercentage;
	}
	public function setDiscountInPercentage($discountInPercentage) {
		$this->discountInPercentage = $discountInPercentage;
	}
}

?>