<?php
class SpecificationCategories{
	private $id;
	private $productCategoryId;
	private $siteId;
	private $specCategoryName;
	public function getId() {
		return $this->id;
	}
	public function setId($id) {
		$this->id = $id;
	}
	public function getProductCategoryId() {
		return $this->productCategoryId;
	}
	public function setProductCategoryId($productCategoryId) {
		$this->productCategoryId = $productCategoryId;
	}
	public function getSiteId() {
		return $this->siteId;
	}
	public function setSiteId($siteId) {
		$this->siteId = $siteId;
	}
	public function getSpecCategoryName() {
		return $this->specCategoryName;
	}
	public function setSpecCategoryName($specCategoryName) {
		$this->specCategoryName = $specCategoryName;
	}
}