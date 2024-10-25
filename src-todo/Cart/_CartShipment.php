<?php

namespace WebImage\Store\Cart;

class _CartShipment {
	var $_address;
	var $_packages = [];
	function __construct($cart_address_struct) {
		$this->_address = $cart_address_struct;
	}
	function addPackage($sku, $quantity, $cart_item_index) {
		array_push($this->_packages, new _CartPackage($sku, $quantity, $cart_item_index));
	}
	function getPackages() {
		return $this->_packages;
	}

	function getAddress() { return $this->_address; }

	function getTotalWeight() {
		$packages = $this->getPackages();
		$weight = 0;
		foreach($packages as $package) {
			$weight += $package->getWeight();
		}
		return $weight;
	}
}