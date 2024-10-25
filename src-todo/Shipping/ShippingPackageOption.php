<?php

namespace WebImage\Store\Shipping;

class ShippingPackageOption {
	var $_id; // ShippingMethodIdentifier
	//var $_currencyCode; // String
	var $_price; // Decimal
	var $_name; // String
	function __construct($shipping_method_identifier, $name, $price) { //, $currency_code=null) {
		$this->_id		= $shipping_method_identifier;
		$this->_name		= $name;
		$this->_price		= $price;
		// $this->_currencyCode	= $currency_code;
	}
	function getId() { return $this->_id; }
	function getPrice() { return $this->_price; }
	function getName() { return $this->_name; }
	// function getCurrencyCode() { return $this->_currencyCode; }
}