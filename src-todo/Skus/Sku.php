<?php

namespace WebImage\Store\Skus;

class Sku {
	var $_code;
	var $_created;
	var $_createdBy;
	var $_cycleLength;
	var $_cycleMode;
	var $_currencyId;
	var $_description;
	var $_enable;
	var $_id;
	var $_licenseAgreementId;
	var $_maxCyclesCount;
	var $_metaClassId;
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
		$this->setCode($sku_struct->code);
		$this->setCreated($sku_struct->created);
		$this->setCreatedBy($sku_struct->created_by);
		$this->setCycleLength($sku_struct->cycle_length);
		$this->setCycleMode($sku_struct->cycle_mode);
		$this->setCurrencyId($sku_struct->currency_id);
		$this->setDescription($sku_struct->description);
		$this->setEnable($sku_struct->enable);
		$this->setId($sku_struct->id);
		$this->setLicenseAgreementId($sku_struct->license_agreement_id);
		$this->setMaxCyclesCount($sku_struct->max_cycles_count);
		$this->setMetaClassId($sku_struct->meta_class_id);
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
	}

	function getCode() { return $this->_code; }
	function getCreated() { return $this->_created; }
	function getCreatedBy() { return $this->_createdBy; }
	function getCycleLength() { return $this->_cycleLength; }
	function getCycleMode() { return $this->_cycleMode; }
	function getCurrencyId() { return $this->_currencyId; }
	function getDescription() { return $this->_description; }
	function getEnable() { return $this->_enable; }
	function getId() { return $this->_id; }
	function getLicenseAgreementId() { return $this->_licenseAgreementId; }
	function getMaxCyclesCount() { return $this->_maxCyclesCount; }
	function getMetaClassId() { return $this->_metaClassId; }
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
	function setEnable($enable) { $this->_enable = $enable; }
	function setId($id) { $this->_id = $id; }
	function setLicenseAgreementId($license_agreement_id) { $this->_licenseAgreementId = $license_agreement_id; }
	function setMaxCyclesCount($max_cycles_count) { $this->_maxCyclesCount = $max_cycles_count; }
	function setMetaClassId($meta_class_id) { $this->_metaClassId = $meta_class_id; }
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
}