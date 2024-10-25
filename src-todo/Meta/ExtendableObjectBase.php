<?php

namespace WebImage\Store\Meta;

class ExtendableObjectBase {
	var $_metaClassId;
	var $_metaClassName;
	var $_id; // MetaObjectId;
	var $_metaFields = []; // MetaFieldCollection

	function __construct($meta_class_id, $meta_object_id) {
		$this->setMetaClassId($meta_class_id);
		$this->setId($meta_object_id);
	}

	function getMetaClassId() { return $this->_metaClassId; }
	function getMetaClassName() { return $this->_metaClassName; }
	// function getMetaObjectId() { return $this->_metaObjectId; }

	function setMetaClassId($meta_class_id) { $this->_metaClassId = $meta_class_id; }
	function setMetaClassName($meta_class_name) { $this->_metaClassName = $meta_class_name; }
	// function setMetaObjectId($meta_object_id) { $this->_metaObjectId = $meta_object_id; }

	function getMetaField($meta_field) {
		if (isset($this->_metaFields[$meta_field])) {
			return $this->_metaFields[$meta_field];
		} else return false;
	}
	function setMetaField($meta_field_key, $meta_field) {
		$this->_metaFields[$meta_field_key] = $meta_field;
	}
	function getMetaFields() {
		return $this->_metaFields;
	}

	function getId() { return $this->_id; }
	function setId($id) { $this->_id = $id; }

	function getXmlObj() {
		$xml_meta_object		= new CWI_XML_Traversal('metaObject');
		$xml_id			= new CWI_XML_Traversal('id', $this->getId());
		$xml_meta_class_id	= new CWI_XML_Traversal('metaClassId', $this->getMetaClassId());
		$xml_meta_class_name	= new CWI_XML_Traversal('metaClassName', $this->getMetaClassName());
		$xml_meta_fields	= new CWI_XML_Traversal('metaFields');

		// Add meta fields
		$meta_fields	= $this->getMetaFields();
		foreach($meta_fields as $meta_field_name=>$meta_field_obj) {
			$xml_meta_field = new CWI_XML_Traversal('metaField');
			$xml_meta_field->setParam('name', $meta_field_name);

			$xml_value = new CWI_XML_Traversal('value', $meta_field_obj->getValue());
			$xml_owner = new CWI_XML_Traversal('owner');
			$xml_field = new CWI_XML_Traversal('field');

			$xml_meta_field->addChild($xml_owner);
			$xml_meta_field->addChild($xml_field);
			$xml_meta_field->addChild($xml_value);

			$xml_meta_fields->addChild($xml_meta_field);
		}

		$xml_meta_object->addChild($xml_id);
		$xml_meta_object->addChild($xml_meta_class_id);
		$xml_meta_object->addChild($xml_meta_class_name);
		$xml_meta_object->addChild($xml_meta_fields);

		return $xml_meta_object;
	}
}