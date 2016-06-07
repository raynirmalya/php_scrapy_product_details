<?php
class AffiliateProductsDetails{
	private $id;
	private $productId;
	private $siteId;
	private $affiliateProductId;
	private $deliveryCharge;
	private $cashOnDelivery;
	private $oneDayDelivery;
	private $probableDeliveryDate;
	private $replacementInDays;
	private $sellingPrice;
	private $originalPrice;
	private $discountInPercentage;
	private $noOfReviews;
	private $ratingOutOfFive;
	private $noOfUsers;
	
	private $attribute1;
	private $attribute2;
	private $attribute3;
	private $attribute4;
	private $productLink;
	
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
	public function getAffiliateProductId() {
		return $this->affiliateProductId;
	}
	public function setAffiliateProductId($affiliateProductId) {
		$this->affiliateProductId = $affiliateProductId;
	}
	public function getSiteId() {
		return $this->siteId;
	}
	public function setSiteId($siteId) {
		$this->siteId = $siteId;
	}
	public function getDeliveryCharge() {
		return $this->deliveryCharge;
	}
	public function setDeliveryCharge($deliveryCharge) {
		$this->deliveryCharge = $deliveryCharge;
	}
	public function getCashOnDelivery() {
		return $this->cashOnDelivery;
	}
	public function setCashOnDelivery($cashOnDelivery) {
		$this->cashOnDelivery = $cashOnDelivery;
	}
	public function getOneDayDelivery() {
		return $this->oneDayDelivery;
	}
	public function setOneDayDelivery($oneDayDelivery) {
		$this->oneDayDelivery = $oneDayDelivery;
	}
	public function getProbableDeliveryDate() {
		return $this->probableDeliveryDate;
	}
	public function setProbableDeliveryDate($probableDeliveryDate) {
		$this->probableDeliveryDate = $probableDeliveryDate;
	}
	public function getReplacementInDays() {
		return $this->replacementInDays;
	}
	public function setReplacementInDays($replacementInDays) {
		$this->replacementInDays = $replacementInDays;
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
	public function getNoOfReviews() {
		return $this->noOfReviews;
	}
	public function setNoOfReviews($noOfReviews) {
		$this->noOfReviews = $noOfReviews;
	}
	public function getRatingOutOfFive() {
		return $this->ratingOutOfFive;
	}
	public function setRatingOutOfFive($ratingOutOfFive) {
		$this->ratingOutOfFive = $ratingOutOfFive;
	}
	public function getNoOfUsers() {
		return $this->noOfUsers;
	}
	public function setNoOfUsers($noOfUsers) {
		$this->noOfUsers = $noOfUsers;
	}
	
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