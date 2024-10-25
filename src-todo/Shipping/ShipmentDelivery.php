<?php

namespace WebImage\Store\Shipping;

class ShipmentDelivery extends ShipmentDeliveryInterface { // using extend since we can't use implements
	var $_methodName;
	var $_serviceSpeed;
	function __construct($method_name, $service_speed=null) {
		$this->setMethodName($method_name);
		if (!is_null($service_speed)) $this->setServiceSpeed($service_speed);
	}
	function getMethodName() { return $this->_methodName; }
	function getServiceSpeed() { return $this->_serviceSpeed; }

	function setMethodName($method_name) { $this->_methodName = $method_name; }
	function setServiceSpeed($service_speed) { $this->_serviceSpeed = $service_speed; }
}