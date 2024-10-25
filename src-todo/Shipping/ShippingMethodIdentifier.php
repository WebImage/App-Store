<?php

namespace WebImage\Store\Shipping;

class ShippingMethodIdentifier extends ShipmentDeliveryInterface {// using extend since we can't use implements
	var $_methodName;
	var $_serviceSpeed;
	function __construct($method_name, $service_speed=null) {
		$this->_methodName = $method_name;
		$this->_serviceSpeed = $service_speed;
	}

	// Get Methods
	function getMethodName() { return $this->_methodName; }
	function getServiceSpeed() { return $this->_serviceSpeed; }

	// Set Methods
	function setMethodName($method_name) { $this->_methodName = $method_name; }
	function setServiceSpeed($service_speed) { $this->_serviceSpeed = $service_speed; }
}
