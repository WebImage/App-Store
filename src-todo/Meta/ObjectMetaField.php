<?php

namespace WebImage\Store\Meta;

class ObjectMetaField {
	var $_isInitialize = true;
	var $m_owner;
	var $m_field;
	var $m_fieldId;
	var $m_value;
	var $m_primaryKeyValue; // Actual primary key (id) of extended/meta class table
	function __construct($meta_object_owner, $meta_field, $value) {
		$this->m_owner = $meta_object_owner;
		$this->m_field = $meta_field;
		$this->m_fieldId = $meta_field->id;
		$this->m_value = $value;
	}

	function getField() { return $this->m_field; }
	function getFieldId() { return $this->m_fieldId; }
	function getValue() { return $this->m_value; }
	function getOwner() { return $this->m_owner; }
	function getPrimaryKeyValue() { return $this->m_primaryKeyValue; }

	function setValue($value) { $this->m_value = $value; }
	function setOwner($owner) { $this->m_owner = $owner; }
	function setPrimaryKeyValue($value) { $this->m_primaryKeyValue = $value; }
}