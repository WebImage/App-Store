<?php

namespace WebImage\Store\Cart;

class ShoppingCartItemFactory {
	public static function createShoppingCartItemFromCartStruct($cart_struct) {
		FrameworkManager::loadLogic('discount');
		FrameworkManager::loadLogic('product');

		#$sku = new Sku( ProductLogic::getSkuById($cart_struct->sku_id) );
		$sku_base = ProductLogic::getSkuBaseById($cart_struct->sku_id);

		$file_key = strtolower($sku_base->getMetaClassName());

		// Check if overriding class exists, otherwise use the default ShoppingCartItem class.  The overriding class must
		$item_created = false;

		if ($meta_class_path = PathManager::translate('~/libraries/metaclasses/shoppingcartitem_' . $file_key . '.php')) {
			include_once($meta_class_path);
			$shopping_cart_item_class = $sku_base->getMetaClassName() . 'ShoppingCartItem';
			if (class_exists($shopping_cart_item_class)) {
				$shopping_cart_item = new $shopping_cart_item_class();
				if (is_a($shopping_cart_item, 'ShoppingCartItem')) {
					$item_created = true;
				}
			}
		}


		if (!$item_created) $shopping_cart_item = new CartItem();

		$shopping_cart_item->setQuantity($cart_struct->quantity);
		$shopping_cart_item->setId($cart_struct->id);

		/**
		 * Get sku information for cart item
		 */

		$shopping_cart_item->setSku($sku_base);

		/**
		 * Get product information for cart item (based on sku info)
		 */
		$product = ProductLogic::getProductById($sku_base->getProductId());
		$shopping_cart_item->setProduct( new Product($product) );

		return $shopping_cart_item;
	}
}