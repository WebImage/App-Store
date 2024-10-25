<?php

namespace WebImage\Store\Meta;

class MetaObjectDAO extends DataAccessObject {
	function loadByIdAndType($object_id, $meta_class_type) {
		$table = $meta_class_type->table_name;
		$sql_select = "
			SELECT *
			FROM `" . $table . "`
			WHERE object_id = '" . $object_id . "'";

		$obj = $this->selectQuery($sql_select);

		return $obj->getAt(0);
	}
}