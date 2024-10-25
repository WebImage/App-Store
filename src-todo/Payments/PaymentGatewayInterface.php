<?php

namespace WebImage\Store\Payments;

class PaymentGatewayInterface {
	var $_paymentOption;
	function charge(&$message) {}
}
