<?php

namespace WebImage\Store\Orders;

class OrderSku {
	private $_order;
	private $_orderSkuStruct;
	private $_parameters = [];
	private $_discounts = [];

	function getOrder() { return $this->_order; }

	/**
	 * @return OrderSkuStruct
	 */
	function getOrderSkuStruct() { return $this->_orderSkuStruct; }
	function getParameters() { return $this->_parameters; }
	/**
	 * Retrieves an order sku parameter.
	 * These parameters are created from cart parameters that are set when an item is originally added to the user's cart.  This parameters allow cart items to be customized.  For example, a person's name could be added to a cart item to customize the cart item.
	 * This method assumes that only one parameter will match the name specified.  If multiple instances are needed, a new function will need to be added to accommodate this.
	 * @access public
	 * @param string $name The name of the parameters, i.e. persons_name
	 * @return string|false Returns the value of the specified parameter, or false if it does not exist
	 */
	function getParam($name) {
		$name = strtolower($name);

		$parameters = $this->getParameters();
		foreach($parameters as $parameter) {
			if ($parameter->parameter == $name) {
				return $parameter->value;
			}
		}
		return false;
	}
	function getDiscounts() { return $this->_discounts; }
	function setOrder($order) {
		$this->_order = $order;
	}
	function setOrderSkuStruct($order_sku_struct) {
		$this->_orderSkuStruct = $order_sku_struct;
	}
	function setParameters($parameters_array) {
		$this->_parameters = $parameters_array;
	}
	function setDiscounts($discount_array) {
		$this->_discounts = $discount_array;
	}
	function onOrderStatusChange() { return true; }

	/**
	 * @return string Special text instructions that can be displayed in an order email, or on the order details page (not yet implemented)
	 */
	function getUserInstructions() { return ''; }
	/**
	 * @return string Text shipping instructions for shipping department only (not yet implemented)
	 */
	function getShippingInstructions() { return ''; }
}