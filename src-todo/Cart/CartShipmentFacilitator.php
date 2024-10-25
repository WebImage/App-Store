<?php

namespace WebImage\Store\Cart;

class CartShipmentFacilitator {
	var $_customerId;
	var $_cart;
	var $_cartItems = [];

	var $_cartShipments = []; // Array of CustomerCartShipmentStruct

	/*
	var $_customerAddressIds = []; // Keep track of shipment addresses

	var $_validAddresses = true;
	*/

	var $_isInitialized = false;

	function __construct($customer_id, $cart) {
		$this->setCustomerId($customer_id);
		$this->setCart($cart);
		$this->setCartItems($cart->getCartItems());
	}

	function getCustomerId() { return $this->_customerId; }
	function getCartItems() {
		$this->_initialize();
		return $this->_cartItems;
	}

	function setCart($cart) { $this->_cart = $cart; }
	function setCustomerId($customer_id) { $this->_customerId = $customer_id; }
	function setCartItems($cart_items=[]) { $this->_cartItems = $cart_items; }

	function getCartShipments() {
		$this->_initialize();
		return $this->_cartShipments;
	}

	function _getCartIndex($cart_id) {
		$cart_items = $this->getCartItems();
		for ($ci=0; $ci < count($cart_items); $ci++) {
			if ($cart_items[$ci]->getId() == $cart_id) {
				return $ci;
			}
		}
		return false;
	}

	function isShippingRequired() {
		$cart_items = $this->getCartItems();
		// Iterate through cart items to check if shipping is required
		foreach($cart_items as $cart_item) {
			$sku = $cart_item->getSku();
			$ship_enabled = $sku->getShipEnabled();

			// Return true on first instance found
			if ($ship_enabled == 1) return true;
		}
		return false;
	}

	function setGlobalAddress($customer_address_struct) {
		FrameworkManager::loadLogic('package');

		if (empty($customer_address_struct->country_id) || empty($customer_address_struct->state_province_id)) return false;

		// Load Logic
		FrameworkManager::loadLogic('country');
		FrameworkManager::loadLogic('stateprovince');

		// Load Structs
		FrameworkManager::loadStruct('customercartshipment');
		FrameworkManager::loadStruct('cartaddress');

		// Get Additional Location (Country, State) Info
		$country	= CountryLogic::getCountryById($customer_address_struct->country_id);
		$state		= StateProvinceLogic::getStateProvinceById($customer_address_struct->state_province_id);

		// Convert customer_address to CustomerCartShipmentStruct
		$cart_shipment = new CustomerCartShipmentStruct();

		$cart_shipment->customer_id			= $customer_address_struct->customer_id;
		$cart_shipment->shipment_address1		= $customer_address_struct->address1;
		$cart_shipment->shipment_address2		= $customer_address_struct->address2;
		$cart_shipment->shipment_city			= $customer_address_struct->city;
		$cart_shipment->shipment_company		= $customer_address_struct->company;
		$cart_shipment->shipment_country_id		= $customer_address_struct->country_id;
		$cart_shipment->shipment_fax			= $customer_address_struct->fax;
		$cart_shipment->shipment_name			= $customer_address_struct->name;
		$cart_shipment->shipment_phone1			= $customer_address_struct->phone1;
		$cart_shipment->shipment_state_province_id	= $customer_address_struct->state_province_id;
		$cart_shipment->shipment_zip			= $customer_address_struct->zip;
		$cart_shipment->shipment_country_iso_code_2	= $country->iso_code_2;
		$cart_shipment->shipment_country_iso_code_3	= $country->iso_code_3;
		$cart_shipment->shipment_country_name		= $country->name;
		$cart_shipment->shipment_state_province_abbrev	= $state->abbrev;
		$cart_shipment->shipment_state_province_name	= $state->name;
		$cart_shipment->shipment_customer_id		= $customer_address_struct->customer_id;

		$shipment = new _CartShipmentWrapper($cart_shipment);

		$cart_items = $this->getCartItems();

		$this->_cartShipments = [];

		for($ci=0; $ci < count($cart_items); $ci++) {
			$sku = $cart_items[$ci]->getSku();

			if ($sku->isShipEnabled()) {
				$cart_address_struct = new CartAddressStruct();
				$cart_address_struct->cart_id = $cart_items[$ci]->getId();
				$cart_address_struct->quantity = $cart_items[$ci]->getQuantity();
				$cart_item_address = new _CartItemAddressWrapper($cart_address_struct, $cart_items[$ci]->getSku(), $ci);

				$shipment->addCartItemAddress($cart_item_address);
			}
		}

		array_push($this->_cartShipments, $shipment);

		$this->_isInitialized = true; // Prevent _initialize() from running
	}

	function _initialize() {
		if (!$this->_isInitialized) {
			$this->_isInitialized = true;
			FrameworkManager::loadLogic('customercartshipment');

			$customer_cart_shipments = CustomerCartShipmentLogic::getShipmentsByCustomerId($this->getCustomerId());

			if (!$this->_isShippingRequired() && $customer_cart_shipments->getCount() > 0) { // This shouldn't happen, but if it does, remove entries for customer cart shipments
				CustomerCartShipmentLogic::deleteAllByCustomerId($this->getCustomerId());
				return 0;
			}

			$cart_items = $this->getCartItems();

			while ($cart_shipment = $customer_cart_shipments->getNext()) {

				$shipment = new _CartShipmentWrapper($cart_shipment);

				// Add Shipment Items
				$cart_shipment_items = CustomerCartShipmentLogic::getCartAddressesByCustomerCartShipmentId($cart_shipment->id);
				while ($cart_address_struct = $cart_shipment_items->getNext()) {

					$cart_index = $this->_getCartIndex($cart_address_struct->cart_id);

					$cart_item_sku = $cart_items[$cart_index]->getSku();

					if ($cart_item_sku->isShipEnabled()) {

						$cart_item_address = new _CartItemAddressWrapper($cart_address_struct, $cart_items[$cart_index]->getSku(), $cart_index);

#						if (!isset($cart_items[$cart_index]->_shipments)) $cart_items[$cart_index]->_shipments = [];

						$this_cart_shipment = new stdClass();
						$this_cart_shipment->shipment = $shipment->getCustomerCartShipmentStruct();
						$this_cart_shipment->address = $cart_address_struct;

						array_push($cart_items[$cart_index]->_shipments, $this_cart_shipment);

						$shipment->addCartItemAddress($cart_item_address);
					}
				}

				// Add Shipment
				array_push($this->_cartShipments, $shipment);
			}

			$this->setCartItems($cart_items);

		}
	}

	function _getNumCartShippableItems() {
		$num_shippable = 0;
		$cart_items = $this->getCartItems();
		foreach($cart_items as $cart_item) {
			$sku = $cart_item->getSku();
			if ($sku->isShipEnabled()) $num_shippable ++;
		}

		return $num_shippable;
	}

	function getPackageShipments() {
		$this->_initialize();

		$cart_shipments = $this->getCartShipments();

		$shipments = [];
		foreach($cart_shipments as $cart_shipment) {
			$shipment = $cart_shipment->getShipment();
			array_push($shipments, $shipment);
		}

		return $shipments;
	}

	function getPackageIds() {
		// Don't think this is needed here: $this->_initialize();

		$package_ids = [];
		$package_shipments = $this->getPackageShipments();

		foreach($package_shipments as $package_shipment) {
			foreach($package_shipment->items as $item) {
				$sku = $item->sku;
				$package = $sku->getPackage();
				if (!in_array($package->id, $package_ids)) array_push($package_ids, $package->id);
			}
		}
		return $package_ids;
	}

	function _isShippingRequired() {
		$package_ids = $this->getPackageIds();
		if ( (count($package_ids) == 0)  && ($this->_getNumCartShippableItems() == 0) ) return false;
		return true;
	}

	function getShippingOptions() {
		$this->_initialize();

		$return_options = new Collection();
		$package_shipments = $this->getPackageShipments();
		$package_ids = $this->getPackageIds();

		if ($this->_isShippingRequired()) {
			FrameworkManager::loadLogic('shippingoption');

			if ($available_options = ShippingOptionLogic::getShippingOptionsByPackageIds($this->getPackageIds())) {

				$total_price = 0;

				while ($shipping_option_shipping_method_struct = $available_options->getNext()) {

					$shipping_method_price = 0;
					$include_method = true;

					foreach($package_shipments as $package_shipment) {
						$price = 0;

						$shipment_delivery = new ShipmentDelivery($shipping_option_shipping_method_struct->shipping_method_name);

						if (ShippingManager::calculateShippingPrice($shipment_delivery, $package_shipment, $price)) {
							$shipping_method_price += $price;
						} else {
							$include_method = false;
						}
					}

					if ($include_method) {
						$option			= new stdClass();
						$option->name		= $shipping_option_shipping_method_struct->shipping_method_name;
						$option->friendly_name	= $shipping_option_shipping_method_struct->friendly_name . ' - $' . number_format($shipping_method_price,2);
						$option->price		= $shipping_method_price;

						$return_options->add($option);
					}

				}

			}

			/*
			if ($return_options->getCount() == 0) {
				$option	= new stdClass();
				$option->name	= '';
				$option->friendly_name = '-- Not Available --';
				$option->price = '';
				$return_options->add($option);
			}*/
		} else {
			/**
			 * Prevent scenario where the number of packages is zero, but the number of shippable cart items is greater than zero.
			 * This happens, for example, when an address has not been specified, therefore cart_shipments have not been setup.
			 */
			if ($this->_getNumCartShippableItems() > 0) {
				// Do Nothing
			} else { // No physical shipments
				$option = new stdClass();
				$option->name = '0';
				$option->friendly_name = '-- Shipping Not Required --';
				$option->price = '';
				$return_options->add($option);
			}
		}


		return $return_options;
	}

	function getShippingOptionsData() {
		return $this->getShippingOptions();
	}

	/*
	function getShippingCost() {
		if ($this->_isShippingRequired()) {
			$package_shipments = $this->getPackageShipments();

			$total_shipping_price = 0;
			foreach($package_shipments as $package_shipment) {
				$price = 0;
				if (ShippingManager::calculateShippingPrice($package_shipment->deliveryMethod, $package_shipment, $price)) {
					$total_shipping_price += $price;
				} else {
					$include_method = false;
				}
			}
			return $total_shipping_price;
		} else return 0;
	}
	*/
}