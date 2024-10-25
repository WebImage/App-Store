<?php

namespace WebImage\Store\Shipping;

class Shipment extends ShipmentInterface {// using extend since we can't use implements
	public function getDestinationAddress() { return $this->destinationAddress; }
	public function getItems() { return $this->items; }
}
