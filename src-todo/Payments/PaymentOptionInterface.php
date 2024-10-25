<?php

namespace WebImage\Store\Payments;

class PaymentOptionInterface {
	var $_errors = [];
	var $_order;

	function validateForm() {}
	function addError($error_message) {}
	function getErrors() {}
	function anyErrors() {}
	function setOrder(&$order) {}
	function getOrder() {}
	function getInterface() {}
	function postProcess() { return true; }
}