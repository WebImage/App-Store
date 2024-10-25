<?php

namespace WebImage\Store\Cart;

class Cart
{
	private array $items = [];
	var $_membershipId;
	/**
	 * Removed because they are calculated/retrieved via getDiscount() & getDiscounts();
	 * var $_discount;
	 * var $_discounts;
	 */
	public function addCartItem($shopping_cart_item)
	{
		$shopping_cart_item->setShoppingCart($this);
		$this->items[] = $shopping_cart_item;
	}

	public function addCartItemByStruct($cart_item_struct, $parameters = [])
	{ // $parameters = array of CartParameterStruct
		//$shopping_cart_item = new ShoppingCartItem($cart_item_struct);
		$shopping_cart_item = ShoppingCartItemFactory::createShoppingCartItemFromCartStruct($cart_item_struct);

		foreach ($parameters as $param) {
			$shopping_cart_item->addParameter($param);
		}
		/*
		foreach($shipments as $address) {
			$shopping_cart_item->addAddress($address);
		}
		*/
		$this->addCartItem($shopping_cart_item);

	}

	public function getItems()
	{ // MemberSkuShoppingCartItem
		return $this->items;
	}

	public function getSubTotal()
	{
		$sub_total = 0;
		foreach ($this->getItems() as $item2) {
			$sub_total += $item2->getTotalPrice();
		}
		return $sub_total;
	}

	/*
	function getTax() {
		$tax = 0;
		foreach ($this->getCartItems() as $item2) {
			$tax += $item2->getTax();
		}
		return $tax;
	}
	*/
	#function getShippingCost() { return 0; }

	/*
	function getTotalPrice() {
		return ($this->getSubTotal() + $this->getTax() + $this->getShippingCost() - $this->getDiscount());
	}
	*/

	public function getDiscount(): float
	{
		$quantity       = 0;
		$customer_items = $this->getItems();//GetCustomerItems();
		if (count($customer_items) == 0) {
			return 0;
		}

		foreach ($customer_items as $item2) {
			$quantity += $item2->getQuantity();
		}
		return DiscountManager::getOrderDiscount($quantity, $this->getSubTotal(), true);
	}

	public function getDiscounts(): array
	{
		return DiscountManager::getOrderDiscounts(true);
	}

	public function getMembershipId(): int
	{
		if (empty($this->_membershipId)) return false;
		else return $this->_membershipId;
	}

	public function setMembershipId($membership_id): void
	{
		$this->_membershipId = $membership_id;
	}

	public function removeCartItemById($id): void
	{
		$this->items = array_filter($this->items, function (CartItem $item) use ($id) {
			return $item->getId() != $id;
		});
	}
}