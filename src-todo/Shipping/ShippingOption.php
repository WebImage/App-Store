<?php

namespace WebImage\Store\Shipping;

class ShippingOption {
	var $_id;
	var $_name;
	var $_price;

	function __construct($shipping_method_name, $friendly_name, $price) {
		$this->_id	= $shipping_method_name;
		$this->_name	= $friendly_name;
		$this->_price	= $price;
	}
	function getId() { return $this->_id; }
	function getPrice() { return $this->_price; }
	function getName() { return $this->_name; }
}