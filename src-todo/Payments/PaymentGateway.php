<?php

namespace WebImage\Store\Payments;

class PaymentGateway extends PaymentGatewayInterface {
	var $_paymentOption;
	function charge(&$message) { return true; }

	function getPaymentOption() { return $this->_paymentOption; }
	function setPaymentOption($payment_option) { $this->_paymentOption = $payment_option; }
}
