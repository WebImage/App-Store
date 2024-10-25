<?php

namespace WebImage\Store\Shipping;

class ShippingGatewayInterface {
	function getRates($shipping_method_id, $package, &$message) {} //ShippingPackageOption[] GetRates(int shippingMethodId, ShipmentInterface shipment, ref string message);
}
