<?php

namespace WebImage\Store\Shipping;

class ShippingManager {
	function calculateShippingPrice($delivery_method, $package, &$price) {

		FrameworkManager::loadLogic('shippingmethod');

		if ($method = ShippingMethodLogic::getShippingMethodByName($delivery_method->getMethodName())) {

			Frameworkmanager::loadLogic('shippingoption');
			$shipping_option = ShippingOptionLogic::getShippingOptionById($method->shipping_option_id);

			// Initialize values
			$class_key	= $shipping_option->system_key;
			$file_key	= $shipping_option->file_key;
			$file_path	= '~/libraries/providers/shipping/' . $file_key . '/' . $file_key . '_gateway.php';

			$price		= 0;
			$message	= '';
			$class_name	= $class_key . 'Gateway';
			$class_file	= $file_path;

			if (is_null($class_name) || empty($class_name)) {
				return false;
			}

			if ($class_file_path = PathManager::translate($class_file)) {
				include_once($class_file_path);

				$method_id = $method->id;
				$shipping_options = ShippingManager::_getShippingRates($method_id, $package, $class_name, $message);

				if (!empty($message)) {
					return false;
				} else {
					while ($option = $shipping_options->getNext()) {
						$price = $option->getPrice();
						return true;
					}
				}
			}
		}

		return false;
	}

	function _getShippingRates($shipping_method_id, $package, $shipping_class, &$message) {
		$gateway = new $shipping_class;
		return $gateway->getRates($shipping_method_id, $package, $message);
	}

	function getShippingOptions($shipment_package) {
		$package_ids = [];
		foreach ($shipment_package->items as $package_item) {

			$sku = $package_item->sku;
			$package_id = $sku->getPackageId();
			if (!in_array($package_id, $package_ids)) {
				array_push($package_ids, $package_id);
			}
		}

		// Get available shipping options based on included packages
		FrameworkManager::loadLogic('shippingoption');
		$available_options = ShippingOptionLogic::getShippingOptionsByPackageIds($package_ids);

		return $available_options;
	}
}