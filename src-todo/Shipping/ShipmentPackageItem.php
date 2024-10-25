<?php

namespace WebImage\Store\Shipping;

class ShipmentPackageItem extends ShipmentPackageItemInterface {
	public function getQuantity() { return $this->quantity; }
	public function getSku() { return $this->sku; }
}
