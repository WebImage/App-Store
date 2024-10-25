<?php

namespace WebImage\Store\Orders;

class OrderSkuFactory {
	public static function createOrderSku($order, $order_item) {
		FrameworkManager::loadLogic('product');

		$sku_base = ProductLogic::getSkuBaseById($order_item['order_item_struct']->sku_id);

		$meta_class = $sku_base->getMetaClassName();
		$file_key = strtolower($meta_class);

		$order_sku_created = false;

		if ($meta_class_path = PathManager::translate('~/libraries/metaclasses/ordersku_' . $file_key . '.php')) {
			include_once($meta_class_path);
			$class_name = $meta_class . 'OrderSku';

			if (class_exists($class_name)) {
				$order_sku = new $class_name();
				if (is_a($order_sku, 'OrderSku')) { // $order_sku class should extend OrderSku
					$order_sku_created = true;
				}
			}
		}

		if (!$order_sku_created) $order_sku = new OrderSku();
		$order_sku->setOrder($order);
		$order_sku->setOrderSkuStruct($order_item['order_item_struct']);
		$order_sku->setParameters($order_item['parameters']);
		$order_sku->setDiscounts($order_item['discounts']);
		return $order_sku;
	}
}
