<?php

namespace WebImage\Store\Orders;

class OrderHelper {

	public static function getFromCart(&$message, $skip_payment_check=false) {

		FrameworkManager::loadStruct('store');

		$customer = Membership::getProvider();
		$user = Membership::getUser();

		if ($user = $customer->getUser()) {
			$cart = $customer->getCart();
		} else {
			$message = 'You must be logged in to create an order.';
			return false;
		}

		$order_struct = new OrderStruct();
		#$order_struct->total				= $cart->getTotalPrice();
		$order_struct->customer_id			= $user->getId();
		$order_struct->email				= $user->getEmail();

		#$order_struct->tax				= $cart->getTax();

		$order = new Order($order_struct);

		$shipping_facilitator = new CartShipmentFacilitator($user->getId(), $cart);
		$cart_items = $shipping_facilitator->getCartItems();
		$shipments = $shipping_facilitator->getCartShipments();

		// Add cart items to order
		$order_total = 0;
		/** @var CourseSkuShoppingCartItem $cart_item */
		foreach($cart_items as $cart_item) {
			/** @var Product $product */
			$product = $cart_item->getProduct();
			/** @var SkuBase $sku */
			$sku = $cart_item->getSku();

			$order_sku = new OrderSkuStruct();

			$order_sku->price		    = $cart_item->getUnitPrice(); //$sku->getPrice();
			$order_sku->quantity		= $cart_item->getQuantity();
			$order_sku->shipped	    	= 0;
			$order_sku->sku_id		    = $sku->getId();
			$order_sku->product_code    = $product->getCode();
			$order_sku->product_name    = $product->getName();
			$order_sku->sku_code        = $sku->getCode();
			$order_sku->sku_name        = $sku->getName();

			$parameters			= [];
			$discounts			= [];

			// Get cart parameters ready for conversion to order_skus parameters
			foreach($cart_item->getParameters() as $parameter) {
				$param = new OrderSkuParameterStruct();
				$param->parameter	= $parameter->parameter;
				$param->value		= $parameter->value;
				array_push($parameters, $param);
			}

			$cart_item_discounts = $cart_item->getDiscounts();

			$order_sku_discount = 0;
			while ($discount = $cart_item_discounts->getNext()) {
				$discount_amount	= $discount->getDiscountAmount($order_sku->price * $order_sku->quantity);
				$order_sku_discount += $discount_amount;

				$discount_history_struct		= new DiscountHistoryStruct();
				$discount_history_struct->amount	= $discount_amount;
				$discount_history_struct->customer_id	= $user->getId();
				$discount_history_struct->discount_id	= $discount->getId();

			}

			$order_sku->discount		= $order_sku_discount;
			$order_sku->total_price		= ($order_sku->price * $order_sku->quantity) - $order_sku->discount;

			$order_total 			+= $order_sku->total_price;

			$order->addOrderItem($order_sku, $parameters, $discounts);

		}

		// Add shipments to order
		$order_shipping_cost = 0;
		foreach($shipments as $shipment) {
			#$shipment_tax = 0;

			// Address
			$shipment_address = $shipment->getCustomerCartShipmentStruct();

			$shipping_cost = $shipment->getShippingCost();

			// Shipment
			$order_shipment				= new OrderShipmentStruct();
			$order_shipment->customer_address_id	= $shipment_address->shipping_address_id;
			$order_shipment->shipping_cost		= $shipping_cost;
			$order_shipment->shipping_method_id	= $shipment_address->shipping_method_id;

			#$order_shipment->shipping_method_friendly_name	= $shipment_address->shipping_method_friendly_name;
			$order_shipment->customer_address_address1		= $shipment_address->shipment_address1;
			$order_shipment->customer_address_address2		= $shipment_address->shipment_address2;
			$order_shipment->customer_address_city			= $shipment_address->shipment_city;
			$order_shipment->customer_address_company		= $shipment_address->shipment_company;
			$order_shipment->customer_address_country_id		= $shipment_address->shipment_country_id;
			$order_shipment->customer_address_customer_id		= $shipment_address->customer_id;
			$order_shipment->customer_address_fax			= $shipment_address->shipment_fax;
			$order_shipment->customer_address_name			= $shipment_address->shipment_name;
			$order_shipment->customer_address_phone1		= $shipment_address->shipment_phone1;
			$order_shipment->customer_address_state_province_id	= $shipment_address->shipment_state_province_id;
			$order_shipment->customer_address_zip			= $shipment_address->shipment_zip;
			$order_shipment->state_province_name			= $shipment_address->shipment_state_province_name;
			$order_shipment->state_province_abbrev			= $shipment_address->shipment_state_province_abbrev;
			$order_shipment->country_name				= $shipment_address->shipment_country_name;
			$order_shipment->country_iso_code_2			= $shipment_address->shipment_country_iso_code_2;
			$order_shipment->country_iso_code_3			= $shipment_address->shipment_country_iso_code_3;

			$order_shipping_cost			+= $shipping_cost;

			/*
				order_id
				shipping_cost
				shipping_method_id
			*/
			$packages = $shipment->getCartItemAddresses();

			$order_shipment_packages = [];

			foreach($packages as $package) {

				$cart_address_struct		= $package->getCartAddressStruct();

				$order_sku_shipment		= new OrderSkuShipmentStruct();
				$order_sku_shipment->quantity	= $cart_address_struct->quantity;

				$order_shipment_packages[] = [
					'order_sku_shipment'	=> $order_sku_shipment,
					'order_item_index'	=> $package->getCartItemIndex()
				];

				/**
				 * Get the sku for this packaged item
				 */
				$sku = $package->getSku();

				/**
				 * Get the tax percentage based on sku tax category and location (country/state)
				 */
				#$tax_percentage = TaxLogic::getTaxPercentageByCategoryIdAndRegionParams($sku->getTaxCategoryId(), $shipment_address->shipment_country_id, $shipment_address->shipment_state_province_id);

				/**
				 * Retrieve the cart item to calculated the total item price.
				 */
				$cart_item = $cart_items[$package->getCartItemIndex()];

				/*
				 * Get cart item unit cost (which includes applied discounts)
				 */
				$cart_item_price = $cart_item->getAverageDiscountedUnitPrice();

				/**
				 * Tax calculation:
				 * Calculation is based on the discounted unit price x the total quantity going to this shipment address
				 */

				#$tax_value = round( ($cart_address_struct->quantity * $cart_item_price * $tax_percentage / 100), 2);

				#$shipment_tax += $tax_value;

			}
			#$order_shipment->tax = $shipment_tax;
			#$order_tax += $shipment_tax;

			$order->addShipment($order_shipment, $order_shipment_packages);

		}

		/**
		 * Track total payments
		 */
		$payment_total = 0;

		if ($payments = CustomerCartPaymentLogic::getUnusedCartPaymentsByCustomerId($user->getId())) {

			while ($payment = $payments->getNext()) {
				$payment_total += (float)$payment->charge;

				// Translate customer cart payment struct to order payment struct
				$order_payment = new PaymentStruct();
				$order_payment->charge				= $payment->charge;
				$order_payment->created				= $payment->created;
				$order_payment->created_by			= $payment->created_by;
				$order_payment->updated				= $payment->updated;
				$order_payment->updated_by			= $payment->updated_by;
				$order_payment->bank_account_name		= $payment->bank_account_name;
				$order_payment->bank_account_number		= $payment->bank_account_number;
				$order_payment->bank_account_type		= $payment->bank_account_type;
				$order_payment->bank_name			= $payment->bank_name;
				$order_payment->bank_routing_number		= $payment->bank_routing_number;
				$order_payment->billing_address_id		= $payment->billing_address_id;
				$order_payment->check_number			= $payment->check_number;
				$order_payment->credit_card_csc			= $payment->credit_card_csc;
				$order_payment->credit_card_expiration		= $payment->credit_card_expiration;
				$order_payment->credit_card_name		= $payment->credit_card_name;
				$order_payment->credit_card_number		= $payment->credit_card_number;
				$order_payment->credit_card_type		= $payment->credit_card_type;
				$order_payment->customer_cart_payment_id	= $payment->id;
				$order_payment->customer_discount		= $payment->customer_discount;
				$order_payment->customer_id			= $payment->customer_id;
				$order_payment->customer_payment_method_id	= $payment->customer_payment_method_id;
				$order_payment->id				= $payment->order_payment_id;
				$order_payment->license_number			= $payment->license_number;
				$order_payment->license_state			= $payment->license_state;
				$order_payment->license_dob			= $payment->license_dob;
				$order_payment->payment_option_id		= $payment->payment_option_id;
				$order_payment->billing_address1		= $payment->billing_address1;
				$order_payment->billing_address2		= $payment->billing_address2;
				$order_payment->billing_city			= $payment->billing_city;
				$order_payment->billing_company			= $payment->billing_company;
				$order_payment->billing_country_id		= $payment->billing_country_id;
				$order_payment->billing_fax			= $payment->billing_fax;
				$order_payment->billing_phone1			= $payment->billing_phone1;
				$order_payment->billing_state_province_id	= $payment->billing_state_province_id;
				$order_payment->billing_zip			= $payment->billing_zip;
				$order_payment->billing_name			= $payment->billing_name;
				$order_payment->billing_country_iso_code_2	= $payment->billing_country_iso_code_2;
				$order_payment->billing_country_iso_code_3	= $payment->billing_country_iso_code_3;
				$order_payment->billing_country_name		= $payment->billing_country_name;
				$order_payment->billing_state_abbrev		= $payment->billing_state_abbrev;
				$order_payment->billing_state_name		= $payment->billing_state_name;

				$order->addPayment($order_payment);
			}

			// Round the float/double values to a reasonable decimal place to account for the problem with comparing FLOATS in PHP (OS problem?)
			#$cart_total = round($cart->getTotalPrice(),3);
			$payment_total = round($payment_total,3);

			/*
			if (!$skip_payment_check && $payment_total != $cart_total) {
				$message = 'There was a problem with your payment information.  Please return to the billing page and re-enter you information.  If the problem persists, please contact support. (E-01)';
				return false;
			}
			*/
		} else {
			$message = 'There was a problem retrieving your payment information.  Please return to the billing information and re-enter your information.  If the problem persists, please contact support. (E-02)';
			return false;
		}

		/**
		 * Set tax for order
		 */

		$order_tax = 0;
		// Calculate tax
		for ($cart_index = 0; $cart_index < count($cart_items); $cart_index++) {
			$cart_item = $cart_items[$cart_index];

			$sku = $cart_items[$cart_index]->getSku();
			$tax_category_id = $sku->getTaxCategoryId();
			$tax_values = TaxLogic::getValuesByCategoryId($tax_category_id);

			$tax_addresses = [];

			while ($tax_value = $tax_values->getNext()) {

				if ($tax_value->percentage > 0) {

					switch ($tax_value->basis) {

						case TAX_BASIS_SHIPPING:
							if ($sku->isShipEnabled()) {
								$total_quantity = 0; // Initial total_quantity so that we can verify the shipment quantities with the cart quantities
								// Each item in different shipment - since we may need to check multiple addresses
								foreach($cart_items[$cart_index]->_shipments as $shipment) {
									$is_tax_potentially_applicable = false;

									$address = new stdClass();
									$address->country_id		= $shipment->shipment->shipment_country_id;
									$address->state_province_id	= $shipment->shipment->shipment_state_province_id;
									$address->zip			= $shipment->shipment->shipment_zip;
									$address->quantity		= $shipment->address->quantity;
									$address->tax_percentage	= $tax_value->percentage;
									$address->tax_id		= $tax_value->tax_id;
									$address->tax_name		= $tax_value->tax_name;
									$address->tax_country_id 	= $tax_value->country_id;
									$address->tax_state_province_id	= $tax_value->state_province_id;
									$address->tax_zip		= $tax_value->zip;
									$address->tax_granularity	= null;

									$total_quantity += $address->quantity;

									if ( empty($address->tax_country_id) && empty($address->tax_state_province_id) && empty($address->tax_zip) ) {
										$is_tax_potentially_applicable = true;
										$address->tax_granularity = TAX_LEVEL_GRANULARITY_GLOBAL;
									} else if ($address->tax_country_id == $address->country_id && empty($address->tax_state_province_id) && empty($address->tax_zip) ) {
										$is_tax_potentially_applicable = true;
										$address->tax_granularity = TAX_LEVEL_GRANULARITY_COUNTRY;
									} else if ($address->tax_country_id == $address->country_id && $address->tax_state_province_id == $address->state_province_id && empty($address->tax_zip)) {
										$is_tax_potentially_applicable = true;
										$address->tax_granularity = TAX_LEVEL_GRANULARITY_STATE;
										/**
										 * Zip code not impelemnted for now
										} else if (!empty($address->tax_zip)) {
										$address->tax_granularity = TAX_LEVEL_GRANULARITY_CITY;
										 */
									}

									if ($is_tax_potentially_applicable) {
										$key = 'tax_id_' . $address->tax_id;
										if (!isset($tax_addresses[$key])) $tax_addresses[$key] = [];
										array_push($tax_addresses[$key], $address);
									}

								}
								if ($total_quantity != $cart_item->getQuantity()) {
									$message = 'There is a problem with one of the items in your cart.  The specified shipped quantity does not match the cart item quantity.';
									return false;
								}
							} else {
								$address = new stdClass();
								$address->country_id = '';
								$address->state_province_id = '';
								$address->zip = '';
								$address->quantity = $cart_item->getQuantity();
								$address->tax_percentage = $tax_value->percentage;
								$address->tax_id = $tax_value->tax_id;
								$address->tax_name = $tax_value->tax_name;
								$address->tax_country_id  = $tax_value->country_id;
								$address->tax_state_province_id = $tax_value->state_province_id;
								$address->tax_granularity = TAX_LEVEL_GRANULARITY_GLOBAL;

								// Since this item does not have a shipment address, only taxes that apply globally will be applied to this item
								if ( empty($address->tax_country_id) && empty($address->tax_state_province_id) && empty($address->tax_zip) ) {
									$key = 'tax_id_' . $address->tax_id;
									if (!isset($tax_addresses[$key])) $tax_addresses[$key] = [];
									array_push($tax_addresses[$key], $address);
								}
							}

							break;
						case TAX_BASIS_BILLING:
							/**
							 * Still need to implement this... probably use order_payment->billing_country_id, etc.
							 */
							break;
						case TAX_BASIS_STORE:
							/**
							 * Still need to implement this... probably use the warehouse that this product will be shipped from
							 */
							break;
					}
				}
			}

			/**
			 * Now that the possible taxes have been added for this item, we need to check which ones ACTUALLY apply based on the most specific possible tax.
			 * For example, if the same tax has a country version and state value, the state value will apply since it is more specific.
			 */

			#$total_tax_percentage = 0;
			$total_item_tax = 0;
			foreach($tax_addresses as $tax_type_key=>$possible_taxes) {

				/**
				 * Only one possible value for each tax can be applied.  The most specific location in relation to the selected address is chosen
				 */
				$applied_tax = null;

				foreach($possible_taxes as $possible_tax) {
					if (is_null($applied_tax)) $applied_tax = $possible_tax;
					else {
						// Check if the possible_tax is more region specific than the currently selected applied_tax
						if ($possible_tax->tax_granularity > $applied_tax->tax_granularity) {
							$applied_tax = $possible_tax;
						}
					}
				}
				if (!is_null($applied_tax)) { // Shouldn't be null, but you never know
					/**
					 * Calculate tax value by applying the calculated tax percentage times
					 * the quantity in this address set by the average discounted unit price
					 *
					 * Rounding is performed only once the entire order tax has been calculated
					 * so that we do not inadvertantly cheat the government out of any
					 * extra pennies - which, as it turns our, also might be illegal in most
					 * (if not all) tax jurisdictions.
					 */
					$tax_value = ( ($applied_tax->tax_percentage / 100) * ($applied_tax->quantity * $cart_item->getAverageDiscountedUnitPrice()) );

					$total_item_tax += $tax_value;
				}
			}

			$order_tax += $total_item_tax;

		}

		$order_tax = round($order_tax, 2);
		$order->setTax( $order_tax ); // Total tax, rounded to two decimal places
		$order->setShippingCost($order_shipping_cost);
		$order->setTotal($order_total + $order_tax + $order_shipping_cost);

		return $order;
	}

	/**
	 * @param $order_id
	 * @return bool|Order
	 */
	public static function getFromOrderId($order_id) {
		FrameworkManager::loadLogic('order');

		if (!$order_struct = OrderLogic::getOrderById($order_id)) return false;

		$order = new Order($order_struct);

		$order_skus = OrderLogic::getOrderSkusByOrderId($order_id);

		while ($order_sku = $order_skus->getNext()) {

			$parameters			= [];
			$discounts			= [];

			// Get cart parameters ready for conversion to order_skus parameters
			$order_sku_parameters = OrderLogic::getOrderSkuParametersByOrderSkuId($order_sku->id);
			while ($param = $order_sku_parameters->getNext()) {
				array_push($parameters, $param);
			}

			$order->addOrderItem($order_sku, $parameters, $discounts);

		}

		/**
		 * Shipments
		 */
		$order_shipments = OrderLogic::getOrderShipmentsByOrderId($order_id);
		$order_tax = 0;
		$order_shipping_cost = 0;
		while($order_shipment = $order_shipments->getNext()) {

			$order_shipment_packages = [];

			$order_sku_shipments = OrderLogic::getOrderSkuShipmentsByOrderShipmentId($order_shipment->id);

			while ($order_sku_shipment = $order_sku_shipments->getNext()) {

				$order_shipment_packages[] = [
					'order_sku_shipment'	=> $order_sku_shipment,
					'order_item_index'	=> 0 /*$package->getCartItemIndex()*/
				];

			}
			$order->addShipment($order_shipment, $order_shipment_packages);

		}

		/**
		 * Payments
		 */
		$payments = OrderLogic::getOrderPaymentsByOrderId($order_id);

		while ($payment = $payments->getNext()) {
			$order->addPayment($payment);
		}

		return $order;
	}

	public static function getFormattedIdFromStruct($order_struct) {
		$order_id = '';
		if (is_object($order_struct)) {
			if (isset($order_struct->id)) {
				$order_id = $order_struct->id;
				if (strlen($order_id) < 3) $order_id = sprintf('%03d', $order_id);

				if (!empty($order_struct->created)) {
					$order_id = database_format_date('Ymd', $order_struct->created) . '-' . $order_id;
				}
			}

		}
		return $order_id;

	}

	public static function getConfirmationHtmlEmail(Order $order) {
		$html = '';
		$html .= '<html><body bgcolor="#e1e1e1" style="background-color:#e1e1e1;font-family: sans-serif; font-size: 12pt;">';
		$html .= '<div style="width:600px;padding:20px;background-color:#fff;margin: 0 auto;">';
		$html .= '<a href="https://olli.csumb.edu/"><img src="https://olli.csumb.edu/assets/olli/img/store/emailheaderlogo.jpg" width="600" height="114" border="0" /></a>';
		$html .= '<h1 style="font-family: sans-serif; font-size:14pt;">Purchase Confirmation</h1>';
		$html .= '<p>Thank you for your order</p>';
		$html .= '<table cellpadding="2" cellspacing="0" border="0" width="560" bgcolor="#ffffff">';
		$html .= '<tr>';
		$html .= '<th>Item</th>';
		$html .= '<th>Unit Price</th>';
		$html .= '<th>Quantity</th>';
		$html .= '<th>Total</th>';
		$html .= '</tr>';
		/** @var OrderSkuStruct $item */
		foreach($order->getOrderItems() as $item) {
			$html .= '<tr>';
			$html .= '<td>' . htmlentities($item['order_item_struct']->product_name) . ' - ' . htmlentities($item['order_item_struct']->sku_name) . '</td>';
			$html .= '<td align="right">$' . number_format($item['order_item_struct']->price, 2) . '</td>';
			$html .= '<td align="center">' . $item['order_item_struct']->quantity . '</td>';
			$html .= '<td align="right">$' . number_format($item['order_item_struct']->total_price, 2) . '</td>';
			$html .= '</tr>';
		}
		$html .= '<tr>';
		$html .= '<td colspan="3" align="right"><b>Total:</b></td>';
		$html .= '<td align="right">$' . number_format($order->getTotal(), 2) . '</td>';
		$html .= '</tr>';
		$html .= '</table>';

		$html .= '<p>Order Date: ' . date('m/d/Y', strtotime($order->getCreated())) . '<br />';
		$html .= 'Order Number: ' . $order->getId() . '</p>';

		$html .= '</div>';
		$html .= '</body></html>';

		return $html;
	}
}