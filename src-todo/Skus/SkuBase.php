<?php

namespace WebImage\Store\Skus;

class SkuBase extends ExtendableObjectBase {
	var $_code;
	var $_created;
	var $_createdBy;
	var $_cycleLength;
	var $_cycleMode;
	var $_currencyId;
	var $_description;
	var $_licenseAgreementId;
	var $_maxCyclesCount;
	var $_name;
	var $_outOfStockVisible;
	var $_packageId;
	var $_packageQty;
	var $_price;
	var $_productId;
	var $_reorderMinQty;
	var $_reserveQty;
	var $_shipEnabled;
	var $_skuTemplateId;
	var $_skuType;
	var $_snPackageId;
	var $_sortorder;
	var $_stockQty;
	var $_taxCategoryId;
	var $_updated;
	var $_updatedBy;
	var $_visible;
	var $_warehouseId;
	var $_weight;

	function __construct($sku_struct=null) {
		if (!is_null($sku_struct)) {
			$this->setCode($sku_struct->code);
			$this->setCreated($sku_struct->created);
			$this->setCreatedBy($sku_struct->created_by);
			$this->setCycleLength($sku_struct->cycle_length);
			$this->setCycleMode($sku_struct->cycle_mode);
			$this->setCurrencyId($sku_struct->currency_id);
			$this->setDescription($sku_struct->description);
			$this->setLicenseAgreementId($sku_struct->license_agreement_id);
			$this->setMaxCyclesCount($sku_struct->max_cycles_count);
			$this->setName($sku_struct->name);
			$this->setOutOfStockVisible($sku_struct->out_of_stock_visible);
			$this->setPackageId($sku_struct->package_id);
			$this->setPackageQty($sku_struct->package_qty);
			$this->setPrice($sku_struct->price);
			$this->setProductId($sku_struct->product_id);
			$this->setReorderMinQty($sku_struct->reorder_min_qty);
			$this->setReserveQty($sku_struct->reserve_qty);
			$this->setShipEnabled($sku_struct->ship_enabled);
			$this->setSkuTemplateId($sku_struct->sku_template_id);
			$this->setSkuType($sku_struct->sku_type);
			$this->setSnPackageId($sku_struct->sn_package_id);
			$this->setSortorder($sku_struct->sortorder);
			$this->setStockQty($sku_struct->stock_qty);
			$this->setTaxCategoryId($sku_struct->tax_category_id);
			$this->setUpdated($sku_struct->updated);
			$this->setUpdatedBy($sku_struct->updated_by);
			$this->setVisible($sku_struct->visible);
			$this->setWarehouseId($sku_struct->warehouse_id);
			$this->setWeight($sku_struct->weight);

			parent::__construct($sku_struct->meta_class_id, $sku_struct->id);
			MetaHelper::extend($this->getMetaClassId(), $this->getId(), $this); // Might move this to functions that need this so that additional calls can be delayed until absolutely needed
		}
	}
	function getCode() { return $this->_code; }
	function getCreated() { return $this->_created; }
	function getCreatedBy() { return $this->_createdBy; }
	function getCycleLength() { return $this->_cycleLength; }
	function getCycleMode() { return $this->_cycleMode; }
	function getCurrencyId() { return $this->_currencyId; }
	function getDescription() { return $this->_description; }
	function getLicenseAgreementId() { return $this->_licenseAgreementId; }
	function getMaxCyclesCount() { return $this->_maxCyclesCount; }
	function getName() { return $this->_name; }
	function getOutOfStockVisible() { return $this->_outOfStockVisible; }
	function getPackageId() { return $this->_packageId; }
	function getPackageQty() { return $this->_packageQty; }
	function getPrice() { return $this->_price; }
	function getProductId() { return $this->_productId; }
	function getReorderMinQty() { return $this->_reorderMinQty; }
	function getReserveQty() { return $this->_reserveQty; }
	function getShipEnabled() { return $this->_shipEnabled; }
	function getSkuTemplateId() { return $this->_skuTemplateId; }
	function getSkuType() { return $this->_skuType; }
	function getSnPackageId() { return $this->_snPackageId; }
	function getSortorder() { return $this->_sortorder; }
	function getStockQty() { return $this->_stockQty; }
	function getTaxCategoryId() { return $this->_taxCategoryId; }
	function getUpdated() { return $this->_updated; }
	function getUpdatedBy() { return $this->_updatedBy; }
	function getVisible() { return $this->_visible; }
	function getWarehouseId() { return $this->_warehouseId; }
	function getWeight() { return $this->_weight; }

	function setCode($code) { $this->_code = $code; }
	function setCreated($created) { $this->_created = $created; }
	function setCreatedBy($created_by) { $this->_createdBy = $created_by; }
	function setCycleLength($cycle_length) { $this->_cycleLength = $cycle_length; }
	function setCycleMode($cycle_mode) { $this->_cycleMode = $cycle_mode; }
	function setCurrencyId($currency_id) { $this->_currencyId = $currency_id; }
	function setDescription($description) { $this->_description = $description; }
	function setLicenseAgreementId($license_agreement_id) { $this->_licenseAgreementId = $license_agreement_id; }
	function setMaxCyclesCount($max_cycles_count) { $this->_maxCyclesCount = $max_cycles_count; }
	function setName($name) { $this->_name = $name; }
	function setOutOfStockVisible($out_of_stock_visible) { $this->_outOfStockVisible = $out_of_stock_visible; }
	function setPackageId($package_id) { $this->_packageId = $package_id; }
	function setPackageQty($package_qty) { $this->_packageQty = $package_qty; }
	function setPrice($price) { $this->_price = $price; }
	function setProductId($product_id) { $this->_productId = $product_id; }
	function setReorderMinQty($reorder_min_qty) { $this->_reorderMinQty = $reorder_min_qty; }
	function setReserveQty($reserve_qty) { $this->_reserveQty = $reserve_qty; }
	function setShipEnabled($ship_enabled) { $this->_shipEnabled = $ship_enabled; }
	function setSkuTemplateId($sku_template_id) { $this->_skuTemplateId = $sku_template_id; }
	function setSkuType($sku_type) { $this->_skuType = $sku_type; }
	function setSnPackageId($sn_package_id) { $this->_snPackageId = $sn_package_id; }
	function setSortorder($sortorder) { $this->_sortorder = $sortorder; }
	function setStockQty($stock_qty) { $this->_stockQty = $stock_qty; }
	function setTaxCategoryId($tax_category_id) { $this->_taxCategoryId = $tax_category_id; }
	function setUpdated($updated) { $this->_updated = $updated; }
	function setUpdatedBy($updated_by) { $this->_updatedBy = $updated_by; }
	function setVisible($visible) { $this->_visible = $visible; }
	function setWarehouseId($warehouse_id) { $this->_warehouseId = $warehouse_id; }
	function setWeight($weight) { $this->_weight = $weight; }

	function getAvailableQty() {
		return ($this->getStockQty() - $this->getReserveQty());
	}

	function getDiscount() {
		$value = 0;
		$discounts = $this->getDiscounts();

		while ($discount = $discounts->getNext()) {
			if (
				(
					!$discount->isVolume() && (
						$discount->getDiscountTypeId() != DISCOUNTTYPE_MOSTEXPENSIVESKU
					)
				) && (
					$discount->getDiscountTypeId() != DISCOUNTTYPE_FREESHIPPING
				)
			) {
				$discount_amount = $discount->getDiscountAmount($this->getPrice());
				$value += $discount_amount;
			}
		}

		return $value;
	}

	function getDiscounts() {

		$discounts = DiscountLogic::getDiscounts();

		$list = new Collection();

		while ($discount_struct = $discounts->getNext()) {
			$discount = new Discount($discount_struct);

			$ignore = false;

			switch ($discount->getDiscountTypeId()) {
				case DISCOUNTTYPE_WHOLEORDER:
					$ignore = true;
					break;

				case DISCOUNTTYPE_WHOLEORDEREXCEPTSKUS:
					$ignore = false;

					if ($by_sku_id = DiscountLogic::getSkuDiscountsBySkuId($this->getId())) {
						while ($discount5 = $by_sku_id->getNext()) { // SkuDiscount
							if ($discount5->discount_id == $discount->getId())
							{
								$ignore = true;
								break;
							}
						}
					}
					break;
				case DISCOUNTTYPE_ALLSKUS:
					$ignore = true;

					if ($by_sku_id = DiscountLogic::getSkuDiscountsBySkuId($this->getId())) {

						while ($discount4 = $by_sku_id->getNext()) {
							if ($discount4->discount_id == $discount->getId()) {
								$ignore = false;
								break;
							}
						}

					}

					break;
				case DISCOUNTTYPE_MOSTEXPENSIVESKU:
					$ignore = true;

					if ($by_sku_id = DiscountLogic::getSkuDiscountsBySkuId($this->getId())) {
						while ($discount6 = $by_sku_id->getNext()) {
							if ($discount6->discount_id == $discount->getId())
							{
								$ignore = false;
								break;
							}
						}
					}

					break;
				case DISCOUNTTYPE_CATEGORYASSIGNED:
					// $categoryDiscount = $discount->CategoryDiscount; // CategoryDiscount
					// $flag2 = false;
					$ignore = false;
					/*
					if (!is_null($categoryDiscount))
					{
						foreach ($categoryDiscount AS $discount8)
						{ // CategoryDiscount
							$category = this->Product->Category; // ObjectCategory
							if (!is_null($category))
							{
								foreach ($category AS $category2)
								{ // ObjectCategory
									// Category category3
									for ($category3 = $category2->Category; !is_null($category3); $category3 = $category3->ParentCategory)
									{
										if ($category3->CategoryId == $discount8->CategoryId)
										{
											#$flag2 = true;
											break;
										}
									}
									if ($flag2)
									{
										break;
									}
								}
							}
							if ($flag2)
							{
								break;
							}
						}
					}
					*/
					break;

				default:
					// Nothing
			}
			/*
			if (!$flag2)
			{
				$flag = true;
			}
			else
			{
				$flag = false;
			}
			*/
			if (!$ignore)
			{
				$list->add($discount);
			}
		}
		return $list;
	}

	function getWarehouse() {
		FrameworkManager::loadLogic('office');
		$office_id	= $this->getWarehouseId();
		$warehouse	= OfficeLogic::getOfficeById($office_id);
		return $warehouse;
	}

	function getPackage() {
		FrameworkManager::loadLogic('package');
		$package_id	= $this->getPackageId();
		$package	= PackageLogic::getPackageById($package_id);
		return $package;
	}

	function isShipEnabled() { return ($this->_shipEnabled == 1); }

	function getXmlObj() {
		$xml_meta_obj = parent::getXmlObj();

		$xml_sku			= new CWI_XML_Traversal('sku');

		$xml_code			= new CWI_XML_Traversal('code',			$this->getCode());
		$xml_created			= new CWI_XML_Traversal('created',		$this->getCreated());
		$xml_createdBy			= new CWI_XML_Traversal('createdBy',		$this->getCreatedBy());
		$xml_cycleLength		= new CWI_XML_Traversal('cycleLength',		$this->getCycleLength());
		$xml_cycleMode			= new CWI_XML_Traversal('cycleMode',		$this->getCycleMode());
		$xml_currencyId			= new CWI_XML_Traversal('currencyId',		$this->getCurrencyId());
		$xml_description		= new CWI_XML_Traversal('description',		$this->getDescription());
		$xml_licenseAgreementId		= new CWI_XML_Traversal('licenseAgreementId',	$this->getLicenseAgreementId());
		$xml_maxCyclesCount		= new CWI_XML_Traversal('maxCyclesCount',	$this->getMaxCyclesCount());
		$xml_name			= new CWI_XML_Traversal('name',			$this->getName());
		$xml_outOfStockVisible		= new CWI_XML_Traversal('outOfStockVisible',	$this->getOutOfStockVisible());
		$xml_packageId			= new CWI_XML_Traversal('packageId',		$this->getPackageId());
		$xml_packageQty			= new CWI_XML_Traversal('packageQty',		$this->getPackageQty());
		$xml_price			= new CWI_XML_Traversal('price',		$this->getPrice());
		$xml_productId			= new CWI_XML_Traversal('productId',		$this->getProductId());
		$xml_reorderMinQty		= new CWI_XML_Traversal('reorderMinQty',	$this->getReorderMinQty());
		$xml_reserveQty			= new CWI_XML_Traversal('reserveQty',		$this->getReserveQty());
		$xml_shipEnabled		= new CWI_XML_Traversal('shipEnabled',		$this->getShipEnabled());
		$xml_skuTemplateId		= new CWI_XML_Traversal('skuTemplateId',	$this->getSkuTemplateId());
		$xml_skuType			= new CWI_XML_Traversal('skuType',		$this->getSkuType());
		$xml_snPackageId		= new CWI_XML_Traversal('snPackageId',		$this->getSnPackageId());
		$xml_sortorder			= new CWI_XML_Traversal('sortorder',		$this->getSortorder());
		$xml_stockQty			= new CWI_XML_Traversal('stockQty',		$this->getStockQty());
		$xml_taxCategoryId		= new CWI_XML_Traversal('taxCategoryId',	$this->getTaxCategoryId());
		$xml_updated			= new CWI_XML_Traversal('updated',		$this->getUpdated());
		$xml_updatedBy			= new CWI_XML_Traversal('updatedBy',		$this->getUpdatedBy());
		$xml_visible			= new CWI_XML_Traversal('visible',		$this->getVisible());
		$xml_warehouseId		= new CWI_XML_Traversal('warehouseId',		$this->getWarehouseId());
		$xml_weight			= new CWI_XML_Traversal('weight',		$this->getWeight());
		$xml_id				= new CWI_XML_Traversal('id',			$this->getId());

		$xml_sku->addChild($xml_code);
		$xml_sku->addChild($xml_created);
		$xml_sku->addChild($xml_createdBy);
		$xml_sku->addChild($xml_cycleLength);
		$xml_sku->addChild($xml_cycleMode);
		$xml_sku->addChild($xml_currencyId);
		$xml_sku->addChild($xml_description);
		$xml_sku->addChild($xml_licenseAgreementId);
		$xml_sku->addChild($xml_maxCyclesCount);
		$xml_sku->addChild($xml_name);
		$xml_sku->addChild($xml_outOfStockVisible);
		$xml_sku->addChild($xml_packageId);
		$xml_sku->addChild($xml_packageQty);
		$xml_sku->addChild($xml_price);
		$xml_sku->addChild($xml_productId);
		$xml_sku->addChild($xml_reorderMinQty);
		$xml_sku->addChild($xml_reserveQty);
		$xml_sku->addChild($xml_shipEnabled);
		$xml_sku->addChild($xml_skuTemplateId);
		$xml_sku->addChild($xml_skuType);
		$xml_sku->addChild($xml_snPackageId);
		$xml_sku->addChild($xml_sortorder);
		$xml_sku->addChild($xml_stockQty);
		$xml_sku->addChild($xml_taxCategoryId);
		$xml_sku->addChild($xml_updated);
		$xml_sku->addChild($xml_updatedBy);
		$xml_sku->addChild($xml_visible);
		$xml_sku->addChild($xml_warehouseId);
		$xml_sku->addChild($xml_weight);
		$xml_sku->addChild($xml_id);

		$xml_meta_obj->addChild($xml_sku);
		return $xml_meta_obj;
	}
}