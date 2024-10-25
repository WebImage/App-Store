<?php

namespace WebImage\Store\Cart;

use WebImage\Store\Products\Product;
use WebImage\Store\Shipping\Shipment;

class CartItem
{
	/**
	 * Removed because they are calculated/retrieved via getDiscount() & getDiscounts();
	 * var $_discount;
	 * var $_discounts;
	 */
	private int     $_id; // cart_id
	private string  $_sku; // Sku
	private Product $_product; // ProductStruct
	private Cart    $_cart; // Reference to parent shopping cart object
	/** @var Shipment[] */
	private array $_shipments = []; // Used during checkout to hold shipments for this particular item
	private int   $_quantity;
	/** @var string[] */
	private array $_errors = []; // Keep track of any errors that might occur

	var $_parameters = []; // Never make private in case sub-classes need to directly manipulate this value

	/*var $_addresses = [];*/

	public function canChangeQuantity()
	{
		return true;
	}

	public function canDelete()
	{
		return true;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function getDisplayName()
	{
		$product = $this->getProduct();
		$name    = $product->getName();

		$sku  = $this->getSku();
		$name .= ' - ' . $sku->getName();

		return $name;
	}

	/**
	 * @return Cart
	 */
	public function getCart(): Cart
	{ // Gets reference to parent shopping cart object
		return $this->_cart;
	}

	public function getTotalPrice(): float
	{
		$total_price = ($this->getUnitPrice() * $this->getQuantity()) - $this->getDiscount();
		if ($total_price < 0) $total_price = 0; // Prevent items from returning negative numbers;
		return $total_price;
	}

	public function getUnitPrice(): float
	{
		/* Removed, because we should store the original price, not the average discount price return ($this->getTotalPrice() / $this->getQuantity()); */
		$sku = $this->getSku();
		return $sku->getPrice();
	}

//	public function getAverageDiscountedUnitPrice(): double {
//		return ($this->getTotalPrice() / $this->getQuantity());
//	}

	public function getQuantity(): int
	{
		return $this->_quantity;
	}

	/**
	 * @return SkuBase
	 */
	public function getSku()
	{
		return $this->_sku;
	}

	public function getProduct(): Product
	{
		return $this->_product;
	}

	public function setCart(Cart $cart)
	{
		$this->_cart = $cart;
	}

	/**
	 * @return float
	 */
	public function getDiscount(): float
	{
		$shopping_cart = $this->getCart();
		$items         = $shopping_cart->getItems();
		return DiscountManager::getDiscount($items, $this);
	}

	/**
	 * @return array Array of discounts
	 */
	public function getDiscounts(): array
	{
		$shopping_cart = $this->getCart();
		$items         = $shopping_cart->getItems();
		return DiscountManager::getDiscounts($items, $this);
	}

	/*
	function getTax() {
		FrameworkManager::loadLogic('tax');
		$sku = $this->getSku();
		$tax_percentage = TaxLogic::getTaxPercentageByCategoryIdAndRegionParams($sku->getTaxCategoryId(), 233, 5);
		$tax_value = round( ($this->getQuantity() * $this->getTotalPrice() * $tax_percentage / 100), 2);
		return $tax_value;
	}
	*/

	public function setId($id)
	{
		$this->_id = $id;
	}

	public function setQuantity($quantity)
	{
		$this->_quantity = $quantity;
	}

	public function setSku($sku)
	{
		$this->_sku = $sku;
	}

	public function setProduct($product)
	{
		$this->_product = $product;
	}

	/*
	function getFinalPrice() {
		return ($this->getTotalPrice() + $this->getTax());
	}*/

	public function addParameter($parameter_struct)
	{
		array_push($this->_parameters, $parameter_struct);
	}

	/*
	public function addAddress($shipping_cart_item_address) {
		array_push($this->_addresses, $shipping_cart_item_address);
	}

	public function resetAddresses() { $this->_addresses = []; }
	*/
	public function getParameters()
	{
		return $this->_parameters;
	}

	/*
	public function getAddresses() {
		return $this->_addresses;
	}
	*/
	public function anyErrors(): bool
	{
		return false;
	}

	public function isValid(): bool
	{
		return true;
	}
//	public function onPreAddToCart()
//	{
//		return true;
//	} // Occurs just prior to an item actually being added to the user's cart to determine whether this action is allowed
//
//	public function onPreCheckout()
//	{
//		return true;
//	} // Occurs just prior to checkout to ensure that this item is still valid in the shopping cart

}