<?php

namespace WebImage\Store\Orders;

class Order {
	var $_orderStruct;
	var $_orderItems = [];
	var $_orderShipments = [];
	var $_orderPayments = [];
	var $_orderDiscounts = [];
	var $_errors = [];

	function __construct($order_struct=null) {
		if (is_null($order_struct)) {
			$order_struct = new OrderStruct();
		}
		$this->setOrderStruct($order_struct);
	}

	function addShipment($order_shipment_struct, $order_shipment_packages) {
		$order_shipment = [
			'order_shipment_struct' => $order_shipment_struct,
			'order_shipment_packages' => $order_shipment_packages
		];
		array_push($this->_orderShipments, $order_shipment);
	}

	/**
	 * @param OrderSkuStruct $order_item_struct
	 */
	function addOrderItem($order_item_struct, $parameters=[], $discounts=[]) {
		// Set number value columns to zero if they are not set
		foreach(['price', 'total_price'] as $var) {
			if (!is_numeric($order_item_struct->$var)) $order_item_struct->$var = 0;
		}

		$order_item = [
			'order_item_struct'	=> $order_item_struct,
			'parameters'		=> $parameters,
			'discounts'		=> $discounts
		];
		array_push($this->_orderItems, $order_item);
	}

	function addOrderDiscount($order_discount_struct) {
		array_push($this->_orderDiscounts, $order_discount_struct);
	}

	function addPayment($payment) {
		array_push($this->_orderPayments, $payment);
	}

	function anyErrors() { return (count($this->_errors) > 0); }

	function getErrors() { return $this->_errors; }
	function getShipments() { return $this->_orderShipments; }
	function getPayments() { return $this->_orderPayments; }
	function getOrderDiscounts() { return $this->_orderDiscounts; }

	function getOrderItems() {
		return $this->_orderItems;
	}

	function getAffiliateId() { return $this->_orderStruct->affiliate_id; }
	function getAffiliatePercent() { return $this->_orderStruct->affiliate_percent; }

	function getCompleted() { return $this->_orderStruct->completed; }
	function getCreated() { return $this->_orderStruct->created; }
	function getCustomerDiscount() { return $this->_orderStruct->customer_discount; }
	function getCustomerId() { return $this->_orderStruct->customer_id; }
	function getEmail() { return $this->_orderStruct->email; }
	function getId() { return $this->_orderStruct->id; }

	function getOrderStatusId() { return $this->_orderStruct->order_status_id; }

	function getShippingCost() { return $this->_orderStruct->shipping_cost; }

	function getTax() { return $this->_orderStruct->tax; }
	function getTotal() { return $this->_orderStruct->total; }

	function getOrderStruct() { return $this->_orderStruct; }

	/**
	 * Safe to remove?
	function getOrderStructWithEncryption() {
	FrameworkManager::loadLogic('encryption');

	$order_struct = $this->getOrderStruct();

	if (!empty($order_struct->bank_account_number))	$order_struct->bank_account_number	= EncryptionLogic::encrypt($order_struct->bank_account_number);
	if (!empty($order_struct->bank_routing_number))	$order_struct->bank_routing_number	= EncryptionLogic::encrypt($order_struct->bank_account_number);
	if (!empty($order_struct->credit_card_number))	$order_struct->credit_card_number	= EncryptionLogic::encrypt($order_struct->bank_account_number);
	if (!empty($order_struct->license_number))	$order_struct->license_number	 	= EncryptionLogic::encrypt($order_struct->bank_account_number);

	return $order_struct;
	}
	 */
	function addError($error_message) { array_push($this->_errors, $error_message); }
	function setAffiliateId($affiliate_id) { $this->_orderStruct->affiliate_id = $affiliate_id; }
	function setAffiliatePercent($affiliate_percent) { $this->_orderStruct->affiliate_percent = $affiliate_percent; }
	function setCompleted($completed) { $this->_orderStruct->completed = $completed; }

	function setCreated($created) { $this->_orderStruct->created = $created; }

	function setCustomerDiscount($customer_discount) { $this->_orderStruct->customer_discount = $customer_discount; }
	function setCustomerId($customer_id) { $this->_orderStruct->customer_id = $customer_id; }
	function setEmail($email) { $this->_orderStruct->email = $email; }
	function setId($id) { $this->_orderStruct->id = $id; }

	function setOrderStruct($order_struct) { $this->_orderStruct = $order_struct; }
	/**
	 * Set order item array
	 * @access private
	 */
	function _setOrderItems($order_items=[]) { $this->_orderItems = $order_items; }
	/**
	 * Set shipments array
	 * @access private
	 */
	function _setShipments($order_shipments) { $this->_orderShipments = $order_shipments; }
	/**
	 * Set payments array
	 * @access private
	 */
	function _setPayments($order_payments) { $this->_orderPayments = $order_payments; }

	function setOrderStatusId($order_status_id) { $this->_orderStruct->order_status_id = $order_status_id; }

	function setShippingCost($shipping_cost) { $this->_orderStruct->shipping_cost = $shipping_cost; }

	function setTax($tax) { $this->_orderStruct->tax = $tax; }
	function setTotal($total) { $this->_orderStruct->total = $total; }

	function onComplete() { return true; }

	function processPayments() {
		$payments = $this->getPayments();
		$success = true;
		foreach($payments as $payment) {
			$payment_option = PaymentManager::getPaymentOptionByPaymentOptionId($payment->payment_option_id);
			$payment_option->setOrder($this);
			$payment_option->setPayment( CustomerPaymentMethodLogic::unlockPaymentStruct($payment) );

			if (!$payment_option->charge()) $success = false;

			// Check for errors
			if ($payment_option->anyErrors()) {
				$success = false;
				// Retrieve errors
				$payment_option_errors = $payment_option->getErrors();
				// Merge payment_option errors into this objects error stack
				foreach($payment_option_errors as $payment_option_error) {
					$this->addError($payment_option_error);
				}
			}
		}
		return $success;
	}

	/**
	 * Finalize order
	 */
	function complete() {
		FrameworkManager::loadLogic('order');

		/**
		 * Save Order Core
		 */
		// Get Order Structure
		$order_struct = $this->getOrderStruct();

		// Update the order
		if (empty($order_struct->order_status_id)) {
			$new_status = OrderLogic::getOrderStatusByKey('new');
			$order_struct->order_status_id = $new_status->id;
			$this->setOrderStruct($order_struct); // Update object's order struct
		}



		if ($order_struct = OrderLogic::save($order_struct)) {
			$this->setOrderStruct($order_struct); // Update object's order struct

			/**
			 * Save Order Items
			 */
			$order_items = $this->getOrderItems();

			for ($i=0; $i < count($order_items); $i++) {
				//$order_sku_struct) {
				$order_items[$i]['order_item_struct']->order_id = $order_struct->id;
				// Save Order Item
				$order_sku = OrderLogic::saveSku($order_items[$i]['order_item_struct']);

				// Save Order Sku Parameters
				for ($pi=0; $pi < count($order_items[$i]['parameters']); $pi++) {
					$order_items[$i]['parameters'][$pi]->order_sku_id = $order_sku->id;
					OrderLogic::saveOrderSkuParameter($order_items[$i]['parameters'][$pi]);
				}

				// Need code for processing sku discounts here:

			}
			// Reset order items to saved values
			$this->_setOrderItems($order_items);

			/**
			 * Save Order Shipments
			 */
			$shipments = $this->getShipments();
			for ($i=0; $i < count($shipments); $i++) {
				$shipments[$i]['order_shipment_struct']->order_id = $order_struct->id;
				/**
				 * Not implemented, should set shipment status:
				 * $shipments[$i]['order_shipment_struct']->shipment_status_id = null;
				 */
				// Save
				if ($shipments[$i]['order_shipment_struct'] = OrderLogic::saveShipment($shipments[$i]['order_shipment_struct'])) {
					// Save Order Shipment Package to OrderShipment
					for ($spi = 0; $spi < count($shipments[$i]['order_shipment_packages']); $spi++) {
						$order_item_index = $shipments[$i]['order_shipment_packages'][$spi]['order_item_index'];
						if (isset($order_items[$order_item_index])) { // This should never be false, but that is exactly why we have to test for it
							$shipments[$i]['order_shipment_packages'][$spi]['order_sku_shipment']->order_shipment_id = $shipments[$i]['order_shipment_struct']->id;
							$shipments[$i]['order_shipment_packages'][$spi]['order_sku_shipment']->order_sku_id = $order_items[$order_item_index]['order_item_struct']->id; //$shipments[$i]['order_shipment_struct']->;
							if (!$shipments[$i]['order_shipment_packages'][$spi]['order_sku_shipment'] = OrderLogic::saveSkuShipment($shipments[$i]['order_shipment_packages'][$spi]['order_sku_shipment'])) {
								// FrameworkManager::log(LOG_LEVEL_CRITICAL, 'order_sku_package');
							}
						} else {
							// FrameworkManager::log(LOG_LEVEL_CRITICAL, 'order_sku_shipment');
						}
					}
				} else {
					// FrameworkManager::log(LOG_LEVEL_CRITICAL, 'order_shipment_struct');
				}

			}
			$this->_setShipments($shipments);
			/**
			 * Save Order Payments
			 */
			$payments = $this->getPayments();
			for ($i=0; $i < count($payments); $i++) {
				#$authorization_code
				$payments[$i]->order_id = $order_struct->id;
				$payments[$i] = OrderLogic::savePayment($payments[$i]);
			}
			$this->_setPayments($payments);

			// Save Order Discounts
		}
		$this->onStatusChange();
		return true;
	}

	function getExtendedOrderSkus() {
		$extended_order_skus = [];
		$order_items = $this->getOrderItems();
		foreach($order_items as $order_item) {
			$order_sku = OrderSkuFactory::createOrderSku($this, $order_item);
			array_push($extended_order_skus, $order_sku);
		}
		return $extended_order_skus;
	}

	function changeStatus($order_status_key) {
		FrameworkManager::loadLogic('order');
		$status = OrderLogic::getOrderStatusByKey($order_status_key);

		$order_struct = $this->getOrderStruct();
		$order_struct->order_status_id = $status->id;
		OrderLogic::save($order_struct);
		$this->setOrderStruct($order_struct); // Update object's order struct
		$this->onStatusChange();
	}

	function onStatusChange() {
		$extended_order_skus = $this->getExtendedOrderSkus();
		foreach($extended_order_skus as $order_item) {
			$order_item->onOrderStatusChange();
		}
	}
}
