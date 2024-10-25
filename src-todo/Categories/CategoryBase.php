<?php

namespace WebImage\Store\Categories;

class CategoryBase extends ExtendableObjectBase {
	var $_created;
	var $_created_by;
	var $_enable;
	var $_is_inherited;
	var $_items_per_page;
	var $_meta_class_id;
	var $_name;
	var $_page_url;
	var $_parent_id;
	var $_site_id;
	var $_sortorder;
	var $_type_id;
	var $_updated;
	var $_updated_by;

	function __construct($category_struct=null) {
		if (!is_null($category_struct)) {
			$this->setCreated($category_struct->created);
			$this->setCreatedBy($category_struct->created_by);
			$this->setEnable($category_struct->enable);
			$this->setIsInherited($category_struct->is_inherited);
			$this->setItemsPerPage($category_struct->items_per_page);
			$this->setMetaClassId($category_struct->meta_class_id);
			$this->setName($category_struct->name);
			$this->setPageUrl($category_struct->page_url);
			$this->setParentCategoryId($category_struct->parent_id);
			$this->setSiteId($category_struct->site_id);
			$this->setSortOrder($category_struct->sortorder);
			$this->setTypeId($category_struct->type_id);
			$this->setUpdated($category_struct->updated);
			$this->setUpdatedBy($category_struct->updated_by);
		}
	}

	function getCreated() { return $this->_created; }
	function getCreatedBy() { return $this->_created_by; }
	function getEnable() { return $this->_enable; }
	function getIsInherited() { return $this->_is_inherited; }
	function getItemsPerPage() { return $this->_items_per_page; }
	function getMetaClassId() { return $this->_meta_class_id; }
	function getName() { return $this->_name; }
	function getPageUrl() { return $this->_page_url; }
	function getParentCategoryId() { return $this->_parent_id; }
	function getSiteId() { return $this->_site_id; }
	function getSortOrder() { return $this->_sortorder; }
	function getTypeId() { return $this->_type_id; }
	function getUpdated() { return $this->_updated; }
	function getUpdatedBy() { return $this->_updated_by; }

	function setCreated($created) { $this->_created = $created; }
	function setCreatedBy($created_by) { $this->_created_by = $created_by; }
	function setEnable($enable) { $this->_enable = $enable; }
	function setIsInherited($is_inherited) { $this->_is_inherited = $is_inherited; }
	function setItemsPerPage($items_per_page) { $this->_items_per_page = $items_per_page; }
	function setMetaClassId($meta_class_id) { $this->_meta_class_id = $meta_class_id; }
	function setName($name) { $this->_name = $name; }
	function setPageUrl($page_url) { $this->_page_url = $page_url; }
	function setParentCategoryId($parent_id) { $this->_parent_id = $parent_id; }
	function setSiteId($site_id) { $this->_site_id = $site_id; }
	function setSortOrder($sortorder) { $this->_sortorder = $sortorder; }
	function setTypeId($type_id) { $this->_type_id = $type_id; }
	function setUpdated($updated) { $this->_updated = $updated; }
	function setUpdatedBy($updated_by) { $this->_updated_by = $updated_by; }

	function getProducts() {
		FrameworkManager::loadLibrary('products');
	}
}