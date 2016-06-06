<?php
class Specification{
	
	private $id;
	private $productId;
	private $siteId;
	private $specCategoryId;
	private $specificationKey;
	private $specificationValue;
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
	public function getSpecCategoryId() {
		return $this->specCategoryId;
	}
	public function setSpecCategoryId($specCategoryId) {
		$this->specCategoryId = $specCategoryId;
	}
	public function getSpecificationKey() {
		return $this->specificationKey;
	}
	public function setSpecificationKey($specificationKey) {
		$this->specificationKey = $specificationKey;
	}
	public function getSpecificationValue() {
		return $this->specificationValue;
	}
	public function setSpecificationValue($specificationValue) {
		$this->specificationValue = $specificationValue;
	}
}

?>