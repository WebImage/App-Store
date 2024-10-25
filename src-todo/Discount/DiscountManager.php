<?php

namespace WebImage\Store\Discount;

class DiscountManager {
	public static function calculateShippingDiscount($cache, $order_shipment, $quantity, $sub_order) {}
	public static function getDiscount($items, $current_item) {

		$discount_amount = 0;
		$num2 = 0;

		$discounts = DiscountManager::getDiscounts($items, $current_item);

		if ($discounts->getCount() == 0) {
			return 0;
		}
		$sku = $current_item->getSku();

		while ($discount = $discounts->getNext()) {
			if ($discount->getDiscountTypeId() != DISCOUNTTYPE_FREESHIPPING) {
				if ($discount->isVolume()) { // if (discount.IsVolume) {
					$discount_amount = $discount->getDiscountAmount($sku->getPrice());
				} else {
					$num2 += $discount->getDiscountAmount($sku->getPrice());
				}

			}
		}
		echo (($discount_amount + $num2) * $current_item->getQuantity());
		return (($discount_amount + $num2) * $current_item->getQuantity());
	}
	public static function getDiscounts($items, $current_item) {

		$sku = $current_item->getSku();

		if (is_null($sku)) {
			return null;
		}

		$discounts = $sku->getDiscounts();

		if ($discounts->getCount() == 0) {
			return new Collection();
		}


		$list = new Collection();
		$quantity = 0;

		while ($discount = $discounts->getNext()) {

			$ignore = false;

			if ($discount->isVolume()) {
				if (($current_item->getQuantity() < $discount->getQuantity()) || ($discount->getQuantity() <= $quantity)) {
					$ignore = true;
				}
				if (!$ignore) {
					$quantity = $discount->getQuantity();
				}
			}

			if (!$ignore) {

				if ($discount->getDiscountTypeId() == DISCOUNTTYPE_MOSTEXPENSIVESKU) {
					$sku_discount = $discount->getSkuDiscounts();

					$ignore = true;
					$price = $sku->getPrice();
					$greatest_price = 0;

					// Iterate thru each cart item to find most expensive items
					foreach($items as $item) {
						$item_sku = $item->getSku();
						$item_sku_id = $item_sku->getId();

						if ($item_sku_id != $sku->getId()) {
							$apply_discount_to_this_item = false;

							$sku_discount->resetIndex();

							while ($discount3 = $sku_discount->getNext()) {
								if ($discount3->sku_id == $item_sku_id) {
									$apply_discount_to_this_item = true;
								}

							}

							if ($apply_discount_to_this_item && $item_sku->getPrice() > $greatest_price) {
								$greatest_price = $item_sku->getPrice();
							}
						}
					}

					// Check if this item is the most expensive item
					if ($price > $greatest_price) {
						$ignore = false;
					}
				}

			}

			if (!$ignore) {
				$list->add($discount);
			}
		}

		return $list;
	}

	public static function getOrderDiscount($quantity, $total, $cache=false) {

		$total_discount = 0;
		$order_discounts = DiscountManager::getOrderDiscounts($cache);

		if ($order_discounts->getCount() == 0) {
			while ($discount = $order_discounts->getNext()) {
				if (!$discount->isVolume() || ($quantity >= $discount->getQuantity())) {
					$discount_amount = $discount->getDiscountAmount($total);
					$total_discount += $discount_amount;
				}
			}
		}

		return $total_discount;
	}

	public static function getOrderDiscounts($cache=false) {

		$list = new Collection();

		if ($discounts = DiscountLogic::getDiscounts()) {
			while ($discount_struct = $discounts->getNext()) {
				$discount = new Discount($discount_struct);

				if ($discount->isAllowed() && $discount->getDiscountTypeId() == DISCOUNTTYPE_WHOLEORDER) {
					// if ((!discount2.Disabled && discount2.IsAllowed) && (discount2.DiscountType == DiscountType.WholeOrder)) {
					$list->add($discount);
				}
			}
		}

		return $list;
	}


	public static function getPercentage($sum, $percentage, $round) {}
}