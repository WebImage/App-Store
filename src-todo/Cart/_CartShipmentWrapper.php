<?php

namespace WebImage\Store\Cart;

class _CartShipmentWrapper { // Wrapper for CustomerCartShipmentStruct to store _CartItemAddressWrapper
	var $_customerCartShipmentStruct;
	var $_cartItemAddresses = [];

	function __construct($customer_cart_shipment_struct) {
		$this->setCustomerCartShipmentStruct($customer_cart_shipment_struct);
	}
	function setCustomerCartShipmentStruct($customer_cart_shipment_struct) {
		$this->_customerCartShipmentStruct = $customer_cart_shipment_struct;
	}

	function addCartItemAddress($cart_item_address_wrapper) {
		array_push($this->_cartItemAddresses, $cart_item_address_wrapper);
	}

	function getCustomerCartShipmentStruct() { return $this->_customerCartShipmentStruct; }
	function getCartItemAddresses() { return $this->_cartItemAddresses; }

	function getShipment() {
		$shipment = new Shipment();

		FrameworkManager::loadStruct('store');

		$customer_cart_shipment_struct			= $this->getCustomerCartShipmentStruct();

		$shipment_delivery = new ShipmentDelivery($customer_cart_shipment_struct->shipping_method_name);

		$customer_address				= new CustomerAddressStruct();
		$customer_address->address1			= $customer_cart_shipment_struct->shipment_address1;
		$customer_address->address2			= $customer_cart_shipment_struct->shipment_address2;
		$customer_address->city				= $customer_cart_shipment_struct->shipment_city;
		$customer_address->company			= $customer_cart_shipment_struct->shipment_company;
		$customer_address->country_id			= $customer_cart_shipment_struct->shipment_country_id;
		$customer_address->customer_id			= $customer_cart_shipment_struct->shipment_customer_id;
		$customer_address->fax				= $customer_cart_shipment_struct->shipment_fax;
		$customer_address->name				= $customer_cart_shipment_struct->shipment_name;
		$customer_address->phone1			= $customer_cart_shipment_struct->shipment_phone1;
		$customer_address->state_province_id		= $customer_cart_shipment_struct->shipment_state_province_id;
		$customer_address->zip				= $customer_cart_shipment_struct->shipment_zip;

		$customer_address->country_iso_code_2		= $customer_cart_shipment_struct->shipment_country_iso_code_2;
		$customer_address->country_iso_code_3		= $customer_cart_shipment_struct->shipment_country_iso_code_3;
		$customer_address->country_name			= $customer_cart_shipment_struct->shipment_country_name;
		$customer_address->state_province_name		= $customer_cart_shipment_struct->shipment_state_province_name;
		$customer_address->state_province_abbrev	= $customer_cart_shipment_struct->shipment_state_province_abbrev;

		$shipment->destinationAddress			= $customer_address;
		$shipment->deliveryMethod			= $shipment_delivery;

		$cart_item_addresses				= $this->getCartItemAddresses();

		foreach($cart_item_addresses as $cart_item_address) {
			$shipment->items = array_merge($shipment->items, $cart_item_address->getPackages());
		}

		return $shipment;
	}

	function getShippingCost() {
		$package_shipment = $this->getShipment();
		$total_shipping_price = 0;
		if (ShippingManager::calculateShippingPrice($package_shipment->deliveryMethod, $package_shipment, $total_shipping_price)) {
			return $total_shipping_price;
		}
		return false;

	}

}