<?php
/**
 * Хелпер подключения сущностей
 * @author ashmits by 21.01.2013 18:52
 *
 */
class My_Entities_Helper
{
	private static $CI;
	
	
	/**
	 * Сохранить связь сущность-сущность
	 * @param Int $entity_id ID вставляемой сущности
	 * @param Int $entity_in_id ID сущность в которую добавляется
	 * @param String $entity_type тип table, image ...
	 * @param String $entity_in_type тип reviews, reviews_articles ...
	 */
	public static function set_entity($entity_id, $entity_in_id, $entity_type, $entity_in_type)
	{
		$CI =& get_instance();
		$CI->load->model(array('model_common'));
		$CI->model_common->insert('entities_includes', array(
			'entity_id' => intval($entity_id),
			'entity_in_id'	=> intval($entity_in_id),
			'entity_type' => (string)$entity_type,
			'entity_in_type' => (string)$entity_in_type
			));
	}
	
	/**
	 * Удалить добавляемую сущность
	 * @param Int $entity_id
	 * @param String $entity_type
	 */
	public static function delete_by_entity($entity_id, $entity_type)
	{
		$CI =& get_instance();
		$CI->load->model(array('model_common'));
		$CI->model_common->delete("entities_includes", array("entity_id" => intval($entity_id), "entity_type" => (string)$entity_type));
	}

	/**
	 * Удалить по ID и типу
	 * @param Int $entity_in_id
	 * @param String $entity_in_type
	 */
	public static function delete_by_entity_in($entity_in_id, $entity_in_type)
	{
		$CI =& get_instance();
		$CI->load->model(array('model_common'));
		$CI->model_common->delete("entities_includes", array("entity_in_id" => intval($entity_in_id), "entity_in_type" => (string)$entity_in_type));
	}
	
	/**
	 * Находим все теги {table id=""}
	 * @param String $text
	 * @return Array|Null
	 * @author ashmits by 22.01.2013 10:40
	 */
	public static function search_tables_entities($text)
	{
		return preg_match_all('/{table\sid="([0-9]+)"}/i', $text, $matches) ? $matches[1] : null;
	}

	
	/**
	 * Замена тегов на таблицы в тексте
	 * @param String $text
	 * @param Int $entity_in_id
	 * @param Int $entity_in_type
	 * @author ashmits by 22.01.2013 10:40
	 */
	public static function set_entities($text, $entity_in_id, $entity_in_type)
	{
		
		self::delete_by_entity_in($entity_in_id, $entity_in_type);
		
		//вставленные таблицы
		$CI =& get_instance();
		$CI->load->library(array('tables_views'));
		$tables_ids = self::search_tables_entities($text);
		
		if (count($tables_ids))
		{
			foreach ($tables_ids as $key=>$table_id)
			{
				if ($CI->tables_views->get_table_by_id($table_id))
				{
					if (!self::search_entity_single((int)$table_id, 'tables', (int)$entity_in_id, (string)$entity_in_type))
					{
						self::set_entity((int)$table_id, (int)$entity_in_id, 'tables', (string)$entity_in_type);
					}
				}
			}
		}
	}
	
	
	public static function get_entity($entity_id, $entity_type)
	{
		$CI =& get_instance();
		$CI->load->model('model_common');
		$entity = $CI->model_common->select_one("entities_includes", array("entity_id" => intval($entity_id), "entity_type" => (string)$entity_type));
		if (!empty($entity))
		{
			return self::set_structure_entities($entity);
		}
		return null;
	}
	
	
	public static function get_entities_by_entity_in($entity_in_id, $entity_in_type)
	{
		$CI =& get_instance();
		$CI->load->model('model_common');
		$entities = $CI->model_common->select("entities_includes", array("entity_in_id" => intval($entity_in_id), "entity_in_type" => (string)$entity_in_type));
		if (!empty($entities))
		{
			return self::set_structure_entities($entities);
		}
		return null;
	}
	
	
	public static function get_entities_in_by_entity($entity_id, $entity_type)
	{
		$CI =& get_instance();
		$CI->load->model('model_common');
		$entities_in = $CI->model_common->select("entities_includes", array("entity_id" => intval($entity_id), "entity_type" => (string)$entity_type));
		if (!empty($entities_in))
		{
			return self::set_structure_entities($entities_in);
		}
		return null;
	}
	
	public static function set_structure_entities($entities_array)
	{
		if (is_array($entities_array))
		{
			$config = get_config();
			$CI =& get_instance();
			$CI->load->helper('my_url');
			//массив сущностей
			$items = $config['change_objects'];
			foreach ($entities_array as $key=>$val)
			{
				if (isset($items[$val['entity_type']]))
				{
					$entities_array[$key]['entity'] = $items[$val['entity_type']];
					$entities_array[$key]['entity_in'] = $items[$val['entity_in_type']];
				}
			}
		}
		return $entities_array;
	}
	
	public static function search_entity_single($entity_id, $entity_type, $entity_in_id, $entity_in_type)
	{
		$CI =& get_instance();
		$CI->load->model('model_common');
		$entity = $CI->model_common->select_one("entities_includes", array(
				"entity_id" => intval($entity_id),
				"entity_type" => (string)$entity_type,
				"entity_in_id" => intval($entity_in_id),
				"entity_in_type" => (string)$entity_in_type));
		if (!empty($entity))
			return $entity;
		else
			return null;
	}
	
}
?>