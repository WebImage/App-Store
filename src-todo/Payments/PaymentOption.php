<?php

namespace WebImage\Store\Payments;

class PaymentOption {
	var $_errors = [];
	var $_order;
	var $_paymentOption;
	#var $_paymentInfo; // PaymentInfo
	var $_payment; // OrderPayment
	var $_params; // Dictionary

	function __construct() {
		// Load required database structure
		FrameworkManager::loadStruct('orderpayment');

		$this->setPayment(new OrderPaymentStruct());
		$this->_params = new Dictionary();
	}

	function getFormFieldName($field_name) {
		$payment_option = $this->getPaymentOption();
		return $payment_option->file_key . '_' . $field_name;
	}

	/**
	 * Double checks that all required fields are defined within the $this->_paymentInfo object
	 * Should be implemented within the implementing class
	 */
	function validatePaymentStruct() {}
	function validateForm() {
		$this->resetErrors();
		$this->bindFormToPayment();
		return $this->validatePaymentStruct();
	}
	function addError($error_message) { array_push($this->_errors, $error_message); }
	function resetErrors() { $this->_errors = []; }
	function anyErrors() { return (count($this->_errors) > 0); }

	function getErrors() { return $this->_errors; }
	function getOrder() { return $this->_order; }
	function getPayment() { return $this->_payment; }
	#function getPaymentInfo() { return $this->_paymentInfo; }
	function getDisplayControl() { return false; }
	function getPaymentOption() { return $this->_paymentOption; }
	function getParam($param_name) { return $this->_params->get($param_name); }

	function setOrder(&$order) { $this->_order = $order; }
	function setPayment($payment) { $this->_payment = $payment; }
	function setPaymentOption($payment_option_struct) {
		// Automatically set the payment info's payment_option_id
		/*
		$payment_info = $this->getPaymentInfo();
		$payment_info_option_id = $payment_info->getPaymentOptionId();
		if (empty($payment_info_option_id)) $payment_info->setPaymentOptionId($payment_option_struct->id);
		$this->setPaymentInfo($payment_info);
		// Store payment option
		*/
		$this->_paymentOption = $payment_option_struct;
	}
	#function setPaymentInfo($payment_info) { $this->_paymentInfo = $payment_info; }
	function setParam($param_name, $param_value) { $this->_params->set($param_name, $param_value); }

	function postProcess() { return true; }

	function charge() {
		$order = $this->getOrder();
		$order_struct = $order->getOrderStruct();
		$charge = $order_struct->total;

		if (empty($charge)) { // Checks for zero (0) or empty

			$order->complete();
			$this->postProcess();
			return true;

		} else {
			if ($payment_gateway = PaymentManager::getPaymentGateway($this)) {

				// Process order & payment
				$message = '';
				if ($payment_gateway->charge($message)) {
					$this->postProcess();
					return true;
				} else {
					$this->addError($message);
				}

			}

		}

		return false;

	}

	/**
	 * Translates $this->_paymentInfo to the form to be used
	 * Should be implemented within the implementing class
	 */
	function bindPaymentToForm() { return false; }
	/**
	 * Translates the form data to the $this->_paymentInfo object
	 * Should be implemented within the implementing class
	 */
	function bindFormToPayment() { return false; }

	/**
	 * Build the interface for the payment option
	 * Structure of compile taken primarily from ControlLogic::getCompiledControlContents(), which gets a control (.html) and processing files(.html.php) - if available
	 */
	function getInterface() {
		$output = '';

		if ($payment_option = $this->getPaymentOption()) {
			FrameworkManager::loadLogic('control');

			$control_file_src = '~/libraries/providers/payment/' . $payment_option->file_key . '/' . $payment_option->file_key . '.html';

			// Make sure control file exists
			if ($control_file = PathManager::translate($control_file_src)) {
				// Open file
				$fp = fopen($control_file, 'r');
				// Initialize control content
				$control_contents = '';
				// Open file
				if ($fp = fopen(PathManager::translate($control_file), 'r')) {
					// Read the file
					while ($read = fread($fp, 1024)) {
						// Pass contents to control content
						$control_contents .= $read;
					}
					// Close file pointer
					fclose($fp);

					$tag_matches = [];
					preg_match_all('/%getFormFieldName\(\'(.+?)\'\)%/', $control_contents, $tag_matches);

					if (isset($tag_matches[0]) && count($tag_matches[0]) > 0) {
						for ($i=0; $i < count($tag_matches[0]); $i++) {
							$control_contents = str_replace($tag_matches[0][$i], $this->getFormFieldName($tag_matches[1][$i]), $control_contents);
						}
					}

					// Compile Control
					$control_object = CompileControl::compile($control_contents);

					ob_start();
					eval($control_object->init_code);
					// Translate payment info to form
					$this->bindPaymentToForm();
#					if ($control_file_code_src = PathManager::translate($control_file_src . '.php')) {
#						include($control_file_code_src);
#					}

					eval($control_object->attach_init_code);
					eval($control_object->render_code);
					$output = ob_get_contents();
					ob_end_clean();
				}
			}
		}

		return $output;
	}
}
