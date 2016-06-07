<?php

class PageLinks{

	private $id;
	private $productId;
	private $siteId;
	private $productTitle;
	private $productLink;
	private $mainLink;
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
	public function getProductTitle() {
		return $this->productTitle;
	}
	public function setProductTitle($productTitle) {
		$this->productTitle = $productTitle;
	}
	public function getProductLink() {
		return $this->productLink;
	}
	public function setProductLink($productLink) {
		$this->productLink = $productLink;
	}
	public function getMainLink() {
		return $this->mainLink;
	}
	public function setMainLink($mainLink) {
		$this->mainLink = $mainLink;
	}
}

?>