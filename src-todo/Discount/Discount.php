<?php

namespace WebImage\Store\Discount;

class Discount {
	var $_description;
	var $_discount_type_id;
	var $_enable;
	var $_end_date;
	var $_fixed_price;
	var $_id;
	var $_name;
	var $_percentage;
	var $_quantity;
	var $_shipping_method_id;
	var $_site_id;
	var $_start_date;

	function __construct($discount_struct) {
		$this->setDescription($discount_struct->description);
		$this->setDiscountTypeId($discount_struct->discount_type_id);
		$this->setEnable($discount_struct->enable);
		$this->setEndDate($discount_struct->end_date);
		$this->setFixedPrice($discount_struct->fixed_price);
		$this->setId($discount_struct->id);
		$this->setName($discount_struct->name);
		$this->setPercentage($discount_struct->percentage);
		$this->setQuantity($discount_struct->quantity);
		$this->setShippingMethodId($discount_struct->shipping_method_id);
		$this->setSiteId($discount_struct->site_id);
		$this->setStartDate($discount_struct->start_date);
	}

	function getDescription() { return $this->_description; }
	function getDiscountTypeId() { return $this->_discount_type_id; }
	function getEnable() { return $this->_enable; }
	function getEndDate() { return $this->_end_date; }
	function getFixedPrice() { return $this->_fixed_price; }
	function getId() { return $this->_id; }
	function getName() { return $this->_name; }
	function getPercentage() { return $this->_percentage; }
	function getQuantity() { return $this->_quantity; }
	function getShippingMethodId() { return $this->_shipping_method_id; }
	function getSiteId() { return $this->_site_id; }
	function getStartDate() { return $this->_start_date; }

	function setDescription($description) { $this->_description = $description; }
	function setDiscountTypeId($discount_type_id) { $this->_discount_type_id = $discount_type_id; }
	function setEnable($enable) { $this->_enable = $enable; }
	function setEndDate($end_date) { $this->_end_date = $end_date; }
	function setFixedPrice($fixed_price) { $this->_fixed_price = $fixed_price; }
	function setId($id) { $this->_id = $id; }
	function setName($name) { $this->_name = $name; }
	function setPercentage($percentage) { $this->_percentage = $percentage; }
	function setQuantity($quantity) { $this->_quantity = $quantity; }
	function setShippingMethodId($shipping_method_id) { $this->_shipping_method_id = $shipping_method_id; }
	function setSiteId($site_id) { $this->_site_id = $site_id; }
	function setStartDate($start_date) { $this->_start_date = $start_date; }

	function getSkuDiscounts() {
		return DiscountLogic::getSkuDiscountsByDiscountId($this->getId());
	}

	function getDiscountAmount($price) {
		if ($this->getFixedPrice() > 0) {
			return $this->getFixedPrice();
		}
		if ($this->getPercentage() > 0) {
			return round((($price * $this->getPercentage()) / 100), 2);
		}
		return 0;
	}

	function getDiscountRestriction() {
		return DiscountLogic::getDiscountRestrictionByDiscountId($this->getId());
	}

	function isDiscountAllowed($items, $customer) {

		if ($this->getStatus() != DISCOUNTSTATUS_ACTIVE) {
			return false;
		}
		$flag = false;
		$discount_restriction = $this->getDiscountRestriction();
		$sku_order = null;
		$flag2 = false;
		$restriction_sku = null;

		if (!empty($discount_restriction)) {
			$flag = true;

			if (count($items) < $discount_restriction->min_items) {
				return false;
			}

			if ($discount_restriction->min_order_total  > 0) {
				$num = 0;
				foreach($items as $item) {
					$sku = $item->getSku();
					$num += $sku->getPrice() * $item->getQuantity();
				}
				if ($discount_restriction->min_order_total > $num) {
					return false;
				}
			}

			/*
			DISCOUNT COUPON CODE
			if ((strlen($discount_restriction->coupon_code) > 0) && (FrameworkContext.Current.DiscountCouponCode.CompareTo($discount_restriction->coupon_code) != 0)) {
				return false;
			}
			*/

			switch ($discount_restriction->restriction_type_id) {
				case DISCOUNTREQUIREMENTS_NONE:
					$flag2 = true;
					break;
				case DISCOUNTREQUIREMENTS_PURCHASEDALLSKUS:
					// restrictionSku = $discount_restriction->RestrictionSku;

					if ($restriction_skus = DiscountLogic::getRestrictionSkusByRestrictionId($restriction_id)) {

						while ($sku2 = $restriction_skus->getNext()) {
							$sku_order = null;
							if (!is_null($customer)) {
								// $skuOrder = $cust.GetSkuOrder(sku2->id);
							} else {
								// $skuOrder = null;
							}
							if (is_null($sku_order)) {
								$flag2 = false;

								$items->resetIndex();
								while ($item2 = $items->getNext()) {
									if ($item2->sku->id == $sku2->id) {
										$flag2 = true;
										break;
									}
								}
								if ($flag2) {
									continue;
								}
								break;
							}
							$flag2 = true;
						}
					}
					break;
				case DISCOUNTREQUIREMENTS_PURCHASEDONEOFSKUS:

					if ($restriction_skus = DiscountLogic::getRestrictionSkusByRestrictionId($restriction_id)) {
						$flag2 = false;

						while ($sku5 = $restriction_sku->getNext()) {
							$sku_order = null;
							if (!is_null($customer)) {
								// $skuOrder = $cust->GetSkuOrder($sku5->id);
							} else {
								// $skuOrder = null;
							}
							if (is_null($sku_order)) {
								$items->resetIndex();
								while ($item3 = $items->getNext()) {
									if ($item3->sku->id == $sku5->id) {
										$flag2 = true;
										break;
									}
								}
								if (!$flag2) {
									continue;
								}
								break;
							}
							$flag2 = true;
							break;
						}
					}
					break;
				case DISCOUNTREQUIREMENTS_PURCHASEDALLSKUSWITHINNDAYS:

					if ($restriction_skus = DiscountLogic::getRestrictionSkusByRestrictionId($restriction_id)) {
						while ($sku4 = $restriction_skus->getNext()) {
							if (is_null($customer)) {
								break;
							}
							$sku_order = $customer->getSkuOrder($sku4->id);
							if (is_null($sku_order)) {
								$flag2 = false;
								break;
							}

							$span = now() - $sku_order->completed;
							if ($span->TotalDays > $discount_restriction->within_days) {
								$flag2 = false;
								break;
							}
							$flag2 = true;
						}
					}
					break;
				case DISCOUNTREQUIREMENTS_PURCHASEDONEOFSKUWITHINNDAYS:

					if ($restriction_skus = DiscountLogic::getRestrictionSkusByRestrictionId($restriction_id)) {

						while ($sku7 = $restriction_skus->getNext()) {

							if (!is_null($customer)) {
								$sku_order = $customer->getSkuOrder($sku7->id);
								if (is_null($sku_order)) {
									$flag2 = false;
									continue;
								}

								$span2 = timediff(now() - $sku_order->completed);

								if ($span2->total_days > $discount_restriction->within_days) {
									$flag2 = false;
									continue;
								}
								$flag2 = true;
							}
							break;
						}
					}
					break;
				case DISCOUNTREQUIREMENTS_PURCHASEDALLSKUSAFTERDATE:

					if ($restriction_skus = DiscountLogic::getRestrictionSkusByRestrictionId($restriction_id)) {

						while ($sku3 = $restriction_skus->getNext()) {
							if (is_null($customer)) {
								break;
							}
							$sku_order = $customer->getSkuOrder($sku3->id);
							if (is_null($sku_order)) {
								$flag2 = false;
								break;
							}
							if ($sku_order->completed < $discount_restriction->purchase_date) {
								$flag2 = false;
								break;
							}
							$flag2 = true;
						}
					}

					break;
				case DISCOUNTREQUIREMENTS_PURCHASEDONEOFSKUAFTERDATE:

					if ($restriction_skus = DiscountLogic::getRestrictionSkusByRestrictionId($restriction_id)) {

						while ($sku6 = $restriction_skus->getNext()) {
							if (!is_null($customer)) {
								$sku_order = $customer->getSkuOrder($sku6->id);
								if (is_null($sku_order)) {
									$flag2 = false;
									continue;
								}
								if ($sku_order->completed < $discount_restriction->purchase_date) {
									$flag2 = false;
									continue;
								}
								$flag2 = true;
							}
							break;
						}
					}
					break;
				case DISCOUNTREQUIREMENTS_CUSTOMERASSIGNED:
					if (!is_null($customer)) {
						$customer_discount = $customer->getCustomerDiscount();
						if (!is_null($customer_discount) && ($customer_discount->discount_id == $this->discount_id)) {
							$flag2 = true;
						}
					}
					break;
			}
			if (!$flag2) {
				return false;
			}

			$discount_history = null;

			switch ($discount_restriction->limit_type_id) {
				case DISCOUNTLIMIT_UNLIMITED:
					return true;

				case DISCOUNTLIMIT_ONETIMEONLY:
					$discount_histories = DiscountLogic::getAllDiscountHistory();

					// DiscountLogic::getTimesUsed($discount_id);
					// DiscountLogic::getTimesUsedByCustomer($discount_id, $customer_id);

					$discount_history = $this->getDiscountHistory();
					if (!is_null($discount_history) && ($discount_history->getCount() >= 1)) {
						while ($history2 = $discount_history->getnext()) {
							if (!is_null($history2->OrderSku) && $history2->OrderSku->Order->Processed) {
								return false;
							}
						}
					}
					return flag;

				case DISCOUNTLIMIT_ONETIMEPERCUSTOMER:
					if (is_null($customer)) {
						return true;
					}
					$discount_history = $this->getDiscountHistory();
					if (is_null($discount_history)) {
						return true;
					}
					while ($history3 = $discount_history->getNext()) {
						if (
							(
								(
									$history3->customer_id == $customer->customer_id
								) &&
								(
								!is_null($history3->order_sku)
								)
							) && $history3->OrderSku->Order->Processed
						) {
							return false;
						}
					}
					return $flag;

				case DISCOUNTLIMIT_FIRSTNCUSTOMERS:
					if ($this->getRedeemedCount() >= $discount_restriction->limit_number) {
						return false;
					}
					return $flag;
			}
		}
		return $flag;
	}

	// Properties
	function getCategoryDiscount() {}
	// function getDiscountHistory() {}
	// function getDiscountRestriction() {}

	function isAllowed() {
		$list = new Collection();

		$user = Membership::getProvider();
		$cart = $user->getCart();
		$cart_items = $cart->getCartItems();

		return $this->isDiscountAllowed($cart_items, $user);
	}
	function isVolume() { return ($this->getQuantity() > 1); }
	function getRedeemedCount() {}

	function getSkuDiscount() {}
	function getStatus() {} // DiscountStatus Status { get; }
	function getTotalAmount() {}
}