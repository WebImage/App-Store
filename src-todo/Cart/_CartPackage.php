<?php

namespace WebImage\Store\Cart;

class _CartPackage {
	var $_cartItemIndex;
	var $_sku; // Sku Object
	var $_quantity;
	function __construct($sku, $quantity, $cart_item_index) {
		$this->_cartItemIndex = $cart_item_index;
		$this->_sku = $sku;
		$this->_quantity = $quantity;
	}

	function getCartItemIndex() { return $this->_cartItemIndex; }
	function getSku() { return $this->_sku; }
	function getQuantity() { return $this->_quantity; }
	function getPackage() {
		$sku = $this->getSku();
		return $sku->getPackage();
	}

	function getWeight() {
		$sku = $this->getSku();
		return ($sku->getWeight() * $this->getQuantity());
	}
}