<?php

namespace WebImage\Store\Products;

use WebImage\Store\Meta\ExtendableObjectBase;

class ProductBase extends ExtendableObjectBase {
	var $_code;
	var $_created;
	var $_createdBy;
	var $_enable;

	var $_isInherited;
	var $_manufacturerId;
	var $_name;
	var $_templateId;
	var $_siteId;
	var $_updated;
	var $_updatedBy;

	// Lowest sku price from sku table
	var $_skuPrice;
	function __construct($product_struct=null) {
		if (!is_null($product_struct) && is_object($product_struct)) {
			$this->setCode($product_struct->code);
			$this->setCreated($product_struct->created);
			$this->setCreatedBy($product_struct->created_by);
			$this->setEnable($product_struct->enable);
			$this->setName($product_struct->name);
			$this->setTemplateId($product_struct->template_id);
			$this->setUpdated($product_struct->updated);
			$this->setUpdatedBy($product_struct->updated_by);
			if (isset($product_struct->sku_price)) $this->setPrice($product_struct->sku_price);

			parent::__construct($product_struct->meta_class_id, $product_struct->id);
		}
	}
	function getCode() { return $this->_code; }
	function getCreated() { return $this->_created; }
	function getCreatedBy() { return $this->_createdBy; }
	function getEnable() { return $this->_enable; }

	function getIsInherited() { return $this->_isInherited; }
	function getManufacturerId() { return $this->_manufacturerId; }
	function getName() { return $this->_name; }
	function getTemplateId() { return $this->_templateId; }
	function getSiteId() { return $this->_siteId; }
	function getUpdated() { return $this->_updated; }
	function getUpdatedBy() { return $this->_updatedBy; }

	function getPrice() { return $this->_skuPrice; }
	function setCode($code) { $this->_code = $code; }
	function setCreated($created) { $this->_created = $created; }
	function setCreatedBy($created_by) { $this->_createdBy = $created_by; }
	function setEnable($enable) { $this->_enable = $enable; }

	function setIsInherited($is_inherited) { $this->_isInherited = $is_inherited; }
	function setManufacturerId($manufacturer_id) { $this->_manufacturerId = $manufacturer_id; }
	function setName($name) { $this->_name = $name; }
	function setTemplateId($template_id) { $this->_templateId = $template_id; }
	function setSiteId($site_id) { $this->_siteId = $site_id; }
	function setUpdated($updated) { $this->_updated = $updated; }
	function setUpdatedBy($updated_by) { $this->_updatedBy = $updated_by; }

	function setPrice($price) { $this->_skuPrice = $price; }

	function getSkus() {
		FrameworkManager::loadLogic('product'); // Just in case it has not already been included...
		FrameworkManager::loadLogic('meta');

		$product_id = $this->getId();

		$skus = ProductLogic::getSkusByProductId($product_id);

		$variations = new Dictionary(); // Holds variations and combinations
		$variation_options = new Dictionary();
		$field_dictionaries = [];

		$return_skus = new Collection();
		while ($sku = $skus->getNext()) {
			$sku_base = new SkuBase($sku);
			#Moved to SkuBase constructor: MetaHelper::extend($sku->meta_class_id, $sku->id, $sku_base);

			$return_skus->add($sku_base);
		}

		return $return_skus;
	}

	function getVariations() {
		FrameworkManager::loadLogic('product'); // Just in case it has not already been included...
		FrameworkManager::loadLogic('meta');

		$product_id = $this->getId();

		$skus = ProductLogic::getSkusByProductId($product_id);

		$variations = new Dictionary(); // Holds variations and combinations
		$variation_options = new Dictionary();
		$field_dictionaries = [];

		//
		// List all combinations
		//
		$combinations = [];

		//
		// Iterate thru skus to get combinations and variations
		//
		while ($sku = $skus->getNext()) {
			$sku_base = new SkuBase($sku);
			// Moved to SkuBase constructor: MetaHelper::extend($sku->meta_class_id, $sku->id, $sku_base);

			$meta_fields = $sku_base->getMetaFields();

			//
			// Keep track of THIS combination
			//
			$combination = [];
			$combination['price'] = $sku_base->getPrice();

			//
			// Loop thru meta fields to obtain values
			//
			foreach($meta_fields as $field_name=>$object_meta_field) {
				$field = $object_meta_field->getField();
				$value = $object_meta_field->getValue();

				$combination[$field->name] = $value;

				if (!$this_variation = $variation_options->get($field_name)) {
					$this_variation = new VariationOptionStruct();
					$this_variation->name		= $field->name;
					$this_variation->friendly_name	= $field->friendly_name;
					$this_variation->options	= [];
				}

				if (strtoupper($field->data_type) == 'DICTIONARYSINGLEVALUE') {
					$dictionary_key = 'dictionary' . $field->id;
					if (!isset($field_dictionaries[$dictionary_key])) {
						$field_dictionaries[$dictionary_key] = [];

						$possible_values = MetaLogic::getDictionaryValuesByMetaFieldId($field->id);

						while ($possible_value = $possible_values->getNext()) {
							$field_dictionaries[$dictionary_key][$possible_value->id] = $possible_value->value;
						}
					}

					if (isset($field_dictionaries[$dictionary_key][$value])) {
						$dictionary_value = $field_dictionaries[$dictionary_key][$value];
						if (!in_array($dictionary_value, $this_variation->options)) {
							$this_variation->options[$value] = $dictionary_value;
						}
					}
				}

				$variation_options->set($field_name, $this_variation);

			}
			$combinations[] = $combination;
		}

		$variations->set('options', $variation_options);
		$variations->set('combinations', $combinations);

		return $variations;
	}

	function getXmlObj() {
		// Get default meta data
		$xml_meta_obj = parent::getXmlObj();

		$xml_product			= new CWI_XML_Traversal('product');
		$xml_code		= new CWI_XML_Traversal('code', $this->getCode());
		$xml_created		= new CWI_XML_Traversal('created', $this->getCreated());
		$xml_created_by		= new CWI_XML_Traversal('createdBy', $this->getCreatedBy());
		$xml_enable		= new CWI_XML_Traversal('enable', $this->getEnable());
		$xml_is_inherited	= new CWI_XML_Traversal('isInherited', $this->getIsInherited());
		$xml_manufacturer_id	= new CWI_XML_Traversal('manufacturerId', $this->getManufacturerId());
		$xml_name		= new CWI_XML_Traversal('name', $this->getName());
		$xml_template_id	= new CWI_XML_Traversal('templateId', $this->getTemplateId());
		$xml_site_id		= new CWI_XML_Traversal('siteId', $this->getSiteId());
		$xml_updated		= new CWI_XML_Traversal('updated', $this->getUpdated());
		$xml_updated_by		= new CWI_XML_Traversal('updatedBy', $this->getUpdatedBy());
		$xml_price		= new CWI_XML_Traversal('price', $this->getPrice());

		$xml_id			= new CWI_XML_Traversal('id', $this->getid());

		$xml_skus		= new CWI_XML_Traversal('skus');

		$xml_product->addChild($xml_code);
		$xml_product->addChild($xml_created);
		$xml_product->addChild($xml_created_by);
		$xml_product->addChild($xml_enable);
		$xml_product->addChild($xml_is_inherited);
		$xml_product->addChild($xml_manufacturer_id);
		$xml_product->addChild($xml_name);
		$xml_product->addChild($xml_template_id);
		$xml_product->addChild($xml_site_id);
		$xml_product->addChild($xml_updated);
		$xml_product->addChild($xml_updated_by);
		$xml_product->addChild($xml_price);
		$xml_product->addChild($xml_id);

		$skus = $this->getSkus();

		while ($sku = $skus->getNext()) {
			$xml_sku = $sku->getXmlObj();
			$xml_skus->addChild($xml_sku);
		}
		$xml_product->addChild($xml_skus);

		$xml_meta_obj->addChild($xml_product);

		return $xml_meta_obj;
	}
}
