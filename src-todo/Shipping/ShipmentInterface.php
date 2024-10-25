<?php

namespace WebImage\Store\Shipping;

class ShipmentInterface {
	var $deliveryMethod; // ShipmentDeliveryInterface
	var $destinationAddress; // CustomerAddressStruct
	var $items = []; // ShipmentInterface
	var $totalPrice; // Decimal
}
