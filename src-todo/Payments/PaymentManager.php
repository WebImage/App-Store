<?php

namespace WebImage\Store\Payments;

class PaymentManager {

	public static function getPaymentOptionByPaymentOptionId($payment_option_id) {
		FrameworkManager::loadLogic('paymentoption');
		$payment_option_struct = PaymentOptionLogic::getPaymentOptionById($payment_option_id);
		return PaymentManager::getPaymentOption($payment_option_struct);
	}

	#public static function getPaymentOption($payment_option_struct, $order=null) {
	public static function getPaymentOption($payment_option_struct) {

		if (is_object($payment_option_struct)) {
			$file_key	= $payment_option_struct->file_key;
			$system_key	= $payment_option_struct->system_key;
			$class_name	= $system_key . 'PaymentOption';

			if ($class_file = PathManager::translate('~/libraries/providers/payment/' . $file_key . '/' . $file_key . '_option.php')) {

				if (@include_once($class_file)) {

					if (class_exists($class_name)) {

						$payment_option = new $class_name;
						$payment_option->setPaymentOption($payment_option_struct);

						FrameworkManager::loadLogic('paymentoptionparameter');

						$params = PaymentOptionParameterLogic::getParametersByPaymentOptionId($payment_option_struct->id);

						if ($params) {
							while ($param = $params->getNext()) {
								$payment_option->setParam($param->parameter, $param->value);
							}
						}

						return $payment_option;
					}
				}

			}
		}

		return false;
	}

	#function getPaymentOptionFromFileKey($payment_gateway_key, $order=null) {
	public static function getPaymentOptionFromFileKey($payment_gateway_key) {
		FrameworkManager::loadLogic('paymentoption');

		if ($payment_option_struct = PaymentOptionLogic::getPaymentOptionByFileKey($payment_gateway_key)) {
			#return PaymentManager::getPaymentOption($payment_option_struct, $order);
			return PaymentManager::getPaymentOption($payment_option_struct);
		}

		return false;
	}

	public static function getPaymentGateway($payment_option) { // $payment_option = PaymentOption

		if ($payment_option_struct = $payment_option->getPaymentOption()) {

			$class_name = $payment_option_struct->system_key . 'PaymentGateway';

			if ($class_file = PathManager::translate('~/libraries/providers/payment/' . $payment_option_struct->file_key . '/' . $payment_option_struct->file_key . '_gateway.php')) {

				if (@include_once($class_file)) {

					if (class_exists($class_name)) {

						$gateway = new $class_name;
						$gateway->setPaymentOption($payment_option);
						return $gateway;


					}

				}

			}

		}

		return false;
	}
}
