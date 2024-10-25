<?php

namespace WebImage\Store\Meta;

class MetaHelper {
	public static function getMetaClassById($meta_class_id) {
		FrameworkManager::loadDAO('metaclass');
		$meta_class_dao = new MetaClassDAO();
		return $meta_class_dao->load($meta_class_id);
	}

	public static function getMetaFields($meta_class_id, $meta_object_id) {
		FrameworkManager::loadDAO('metafield');
		$meta_class_by_id = MetaHelper::getMetaClassById($meta_class_id);

		$meta_object_dao = new MetaObjectDAO();

		$owner = $meta_object_dao->loadByIdAndType($meta_object_id, $meta_class_by_id);

		$meta_field_dao = new MetaFieldDAO();
		$fields = $meta_field_dao->getFieldsByMetaClassId($meta_class_id);

		$list = new Collection();
		while ($field = $fields->getNext()) {
			$value_field = $field->name;
			@$list->add(
				new ObjectMetaField(
					$owner,
					$field,
					$owner->$value_field)
			);
		}

		return $list;
	}

	/**
	 * Extends an object based on a ExtendableObjectBase object
	 *
	 */
	public static function extend($meta_class_id, $meta_object_id, &$object_base) {

		if (!empty($meta_class_id)) {
			/**
			 * Get all meta fields associated with the meta_class
			 */

			$meta_fields = MetaHelper::getMetaFields($meta_class_id, $meta_object_id);

			$meta_class = MetaHelper::getMetaClassById($meta_class_id);
			$object_base->setMetaClassName($meta_class->name);

			$object_table_name = DatabaseManager::getTable($meta_class->table_name);

			if (!empty($object_table_name)) {
				$sql = "SELECT * FROM `" . $object_table_name . "` WHERE object_id = '" . $meta_object_id . "'";

				$data_object = new DataAccessObject();
				$data_object->setCacheResults(false);

				$product_extension_dao = $data_object->selectQuery($sql);

				if ($product_extension_dao->getCount() == 0) {
					$data_object->commandQuery("INSERT INTO `" . $object_table_name . "` (object_id) VALUES('" . $meta_object_id . "')");
					$product_extension_dao = $data_object->selectQuery($sql);
				}

				$product_extension = $product_extension_dao->getAt(0);

				$table_fields = [];
				while ($field = $meta_fields->getNext()) {
					$field_name = $field->m_field->name;

					if (isset($product_extension->$field_name)) $field->setValue($product_extension->$field_name);
					else $field->setValue(null);
					$field->setOwner($meta_class);

					$primary_key = $meta_class->primary_key;
					$primary_key_value = $product_extension->$primary_key;
					$field->setPrimaryKeyValue($primary_key_value);

					$object_base->setMetaField($field_name, $field);
				}
			}
		}
		return $object_base;
	}

	public static function saveMetaObject($extendable_object_base) {
		if (is_object($extendable_object_base) && is_a($extendable_object_base, 'ExtendableObjectBase')) {
			$meta_fields = $extendable_object_base->getMetaFields();
			$update_fields = [];
			foreach($meta_fields as $meta_key=>$meta_field) {
				$owner			= $meta_field->getOwner();
				$field			= $meta_field->getField();
				$table_name		= $owner->table_name;
				$primary_key		= $owner->primary_key;
				$primary_key_value	= $meta_field->getPrimaryKeyValue();

				if (!isset($update_fields[$table_name])) {
					$update_fields[$table_name] = new stdClass();
					$update_fields[$table_name]->data = new stdClass();
					$update_fields[$table_name]->updateFields = ['object_id'];
					$update_fields[$table_name]->tableName = $table_name;
					$update_fields[$table_name]->primaryKey = $primary_key;
					$update_fields[$table_name]->data->$primary_key = $primary_key_value;
					$update_fields[$table_name]->data->object_id = $extendable_object_base->getId();
				}
				$field_name = $field->name;
				$update_fields[$table_name]->data->$field_name = $meta_field->getValue();
				$update_fields[$table_name]->updateFields[] = $field_name;
			}
			foreach($update_fields as $table_info) {
				$access_object = new DataAccessObject();
				$access_object->tableName = DatabaseManager::getTable($table_info->tableName);
				//$access_object->primaryKey = $table_info->primaryKey;
				$access_object->primaryKey = $table_info->primaryKey;
				$access_object->updateFields = $table_info->updateFields;
				$access_object->save($table_info->data);
			}
		} else {
			return false;
		}

	}

	/**
	 * Takes object base and builds forms; also sets meta object values to match submitted form data.
	 */
	public static function getFormHtml(&$extendable_object_base) {
		FrameworkManager::loadLogic('assetmanager');
		FrameworkManager::loadDAO('asset');

		$meta_fields = $extendable_object_base->getMetaFields();
		$form_html = '';


		foreach ($meta_fields as $meta_key=>$object_meta_field) {

			// MetaClass Struct
			$meta_class_struct	= $object_meta_field->getOwner();
			$table_name		= $meta_class_struct->table_name;

			// Field Struct
			$meta_field_struct	= $object_meta_field->getField();

			// Field Properties
			$field_description	= $meta_field_struct->description;
			$field_friendly_name	= $meta_field_struct->friendly_name;
			$field_data_type	= $meta_field_struct->data_type;
			$field_name		= $meta_field_struct->name;
			$field_length		= $meta_field_struct->length;

			// Field Value
			$full_field_name = 'metaeditable' . $table_name . '__' . $field_name;

			if ( ($submitted_value = Page::get($full_field_name)) && (strtoupper($field_data_type) != 'IMAGEFILE') ) {
				$value		= $submitted_value;
				$object_meta_field->setValue($value);
			} else {
				$value		= $object_meta_field->getValue();
			}

			// Not needed? $field_values[$table_name][$field_name] = $value;

			#$form_html .= '<tr><td colspan="2"><hr /></td></tr>';

			$full_field_name = 'metaeditable' . $table_name . '__' . $field_name;

			$form_html .= '<tr><td class="field">' . $field_friendly_name . ': </td>';
			$form_html .= '<td class="value">';

			switch (strtoupper($field_data_type)) {
				case 'TEXT':
				case 'FORMATTEDTEXT':
					$enable_editor = (strtoupper($field_data_type) == 'FORMATTEDTEXT');
					FrameworkManager::loadControl('input');
					$input_control = new InputControl([
														  'id'		=> $full_field_name,
														  'type'		=> 'textarea',
														  'enableEditor'	=> $enable_editor,
														  'value'		=> $value
													  ]);

					$form_html .= $input_control->render();
					break;
				case 'DICTIONARYSINGLEVALUE':
					FrameworkManager::loadControl('select');
					$select_control = new SelectControl([
															'id'		=> $full_field_name,
															'selected'	=> $value,
															'defaultText'	=> '-- Select --',
															'textField'	=> 'value'
														]);

					FrameworkManager::loadLogic('meta');
					$dictionary_values = MetaLogic::getDictionaryValuesByMetaFieldId($meta_field_struct->id);
					$select_control->setData($dictionary_values);

					$form_html .= $select_control->render();
					break;
				case 'IMAGEFILE':

					$asset_struct = new AssetStruct();

					if (!empty($value)) {
						$asset_struct = AssetManagerLogic::getAssetById($value);
					}

					if (Page::isPostBack()) {
						$upload = new CWI_HTML_FORM_ImageFileUpload($full_field_name, ConfigurationManager::get('DIR_FS_ASSETS_WMS'));
						$upload->handleUpload();
						if ($upload->isFile()) {
							if ($upload->isError()) {
								ErrorManager::addError('There was a problem uploading your file ('.$upload->getError() . ' PATH: ' . ConfigurationManager::get('DIR_FS_ASSETS_WMS') . ')');
							} else {
								// mc=meta class; mf=meta field; mo=meta object id
								$tag = 'mc' . $meta_class_struct->id .'-mf' . $object_meta_field->getFieldId() . '-mo' . $object_meta_field->getPrimaryKeyValue() . '-';

								$asset_struct->asset_type_id = 1; // Image
								$asset_struct->file_src		= $upload->getWSPath();
								$asset_struct->width		= $upload->getWidth();
								$asset_struct->height		= $upload->getHeight();
								$asset				= AssetManagerLogic::saveUpload($upload, $asset_struct, $tag);
								$asset_struct->id		= $asset->getId();

								$value = $asset_struct->id;
								$object_meta_field->setValue($value);
							}
						}
					}

					$form_html .= '<input type="file" name="' . $full_field_name . '" />';

					if (!empty($asset_struct->file_src)) {
						if ($asset_struct->width > 200) {
							$display_width = 200;
							$display_height = ceil( $display_width / $asset_struct->width * $asset_struct->height );
						} else {
							$display_width = $asset_struct->width;
							$display_height = $asset_struct->height;
						}
						$form_html .= '<br />';
						$form_html .= '<img src="' . $asset_struct->file_src . '" width="' . $display_width . '" height="' . $display_height . '" border="0" />';
					}

					break;
				default:
					#$form_html .= '<input type="text" name="' . $full_field_name . '" value="' . $value . '" />';

					#$enable_editor = (strtoupper($field_data_type) == 'FORMATTEDTEXT');
					FrameworkManager::loadControl('input');
					$input_control = new InputControl([
														  'id'		=> $full_field_name,
														  'type'		=> 'text',
														  'value'		=> $value
													  ]);

					$form_html .= $input_control->render();
					break;
			}

			$form_html .= '<br /><em>' . $field_description . '</em></td></tr>';

			$extendable_object_base->setMetaField($meta_key, $object_meta_field);
		}
		#if (!empty($form_html)) $form_html = '<table cellspacing="0" cellpadding="0" border="0" class="detaileditview">' . $form_html . '</table>';
		if (empty($form_html)) $form_html = '<tr><td colspan="2">Either there are not any attributes for this item, or you have not saved this item for the first time.</td></tr>';
		return $form_html;
	}
}