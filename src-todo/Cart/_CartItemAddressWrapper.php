<?php

namespace WebImage\Store\Cart;

class _CartItemAddressWrapper { // Wrapper for CartAddressStruct to store additional info
	var $_cartAddressStruct;
	var $_cartItemIndex;
	var $_sku;

	function __construct($cart_address_struct, $sku, $cart_item_index=null) {
		$this->setCartItemIndex($cart_item_index);
		$this->setCartAddressStruct($cart_address_struct);
		$this->setSku($sku);
	}

	// Getters
	function getCartItemIndex() { return $this->_cartItemIndex; }
	function getCartAddressStruct() { return $this->_cartAddressStruct; }
	function getSku() { return $this->_sku; }

	function getPackages() {
		$sku			= $this->getSku();
		$cart_address_struct	= $this->getCartAddressStruct();

		$skus_per_package	= $sku->getPackageQty();
		$quantity_to_ship	= $cart_address_struct->quantity;

		$packages_to_create	= ceil($quantity_to_ship / $skus_per_package);
		$qty_in_package		= $skus_per_package;

		$packages		= [];

		while ($packages_to_create > 0) {
			if ($qty_in_package > $packages_to_create) $qty_in_package = $packages_to_create;
			$packages_to_create -= $qty_in_package;

			$shipment_package_item			= new ShipmentPackageItem();
			$shipment_package_item->quantity	= $qty_in_package;
			$shipment_package_item->sku		= $sku;
			array_push($packages, $shipment_package_item);
		}

		return $packages;
	}

	// Setters
	function setCartItemIndex($index) { $this->_cartItemIndex = $index; }
	function setCartAddressStruct($cart_address_struct) { $this->_cartAddressStruct = $cart_address_struct; }
	function setSku($sku) { $this->_sku = $sku; }

}