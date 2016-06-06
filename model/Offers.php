<?php
class Offers{
	private $id;
	private $offerDetails;
	private $productId;
	private $siteId;
	
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
	public function getOfferDetails() {
		return $this->offerDetails;
	}
	public function setOfferDetails($offerDetails) {
		$this->offerDetails = $offerDetails;
	}
	public function getSiteId() {
		return $this->siteId;
	}
	public function setSiteId($siteId) {
		$this->siteId = $siteId;
	}	
	
}