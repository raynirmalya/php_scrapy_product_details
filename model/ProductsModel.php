<?php
class Products{
	private $productId;
	private $productTitle;
	private $brand;
	private $model;
	private $lastPrice;
	private $availabilty;
	private $relatedTo;
	private $categoryId;
	
	
	public function getProductId() {
		return $this->productId;
	}
	public function setProductId($productId) {
		$this->productId = $productId;
	}
	public function getProductTitle() {
		return $this->productTitle;
	}
	public function setProductTitle($productTitle) {
		$this->productTitle = $productTitle;
	}
	public function getBrand() {
		return $this->brand;
	}
	public function setBrand($brand) {
		$this->brand = $brand;
	}	
	public function getModel() {
		return $this->model;
	}
	public function setModel($model) {
		$this->model = $model;
	}
	public function getLastPrice() {
		return $this->lastPrice;
	}
	public function setLastPrice($lastPrice) {
		$this->lastPrice = $lastPrice;
	}
	public function getAvailability() {
		return $this->availability;
	}
	public function setAvailability($availability) {
		$this->availability = $availability;
	}
	public function getRelatedTo() {
		return $this->relatedTo;
	}
	public function setRelatedTo($relatedTo) {
		$this->relatedTo = $relatedTo;
	}
	public function getCategoryId() {
		return $this->categoryId;
	}
	public function setCategoryId($categoryId) {
		$this->categoryId = $categoryId;
	}
	
}