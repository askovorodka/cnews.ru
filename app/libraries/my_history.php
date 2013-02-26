<?php 

class My_History
{
	
	
	private $CI = null;
	private $date = null;
	private $user_id = null;
	public $change_types = null;
	public $change_objects = null;
	
	
	function __construct()
	{
		
		$this->CI =& get_instance();
		$this->CI->load->model(array('model_common','model_user'));
		$this->CI->load->library(array('my_users'));
		$this->change_types = $this->CI->config->item('change_types');
		$this->change_objects = $this->CI->config->item('change_objects');
		$this->CI->my_users->get_active_admin_user();
		$this->user_id = $this->CI->my_users->active_admin_user['user_id'];
		
	}
	
	
	function add_to_history($object_name = null, $change_type = null, $id = null, $desctiption = null)
	{
		
		if (empty($object_name) or empty($change_type) or empty($id))
		{
			show_error("Переданы не все аргументы в обновление истории");
		}
		
		if (!in_array($object_name, array_keys($this->change_objects)))
		{
			show_error("Неизвестный модуль/объект, добавить в историю невозможно");
		}
		
		if (!in_array($change_type, array_keys($this->change_types)))
		{
			show_error("Неизвестный тип изменения объекта");
		}
		
		$this->date = date("Y-m-d H:i:s");
		
		$this->CI->model_common->insert("change_histories", array(
				'change_type' => $change_type,
				'change_object' => $object_name,
				'change_user_id' => $this->user_id,
				'change_date' => $this->date,
				'object_id' => intval($id),
				'change_description' => (string)$desctiption));
		
	}
	
	
	
	function get_history_changes_list($hash = null, $page)
	{
	
		$chistory_changes_list = $this->CI->model_user->get_history_changes_list($this->get_history_changes_where($hash), $this->get_history_changes_order($hash), intval($page));
		
		if (count($chistory_changes_list))
		{
			foreach ($chistory_changes_list as $key=>$val)
			{
	
				$user = $this->CI->model_common->select_one("users", array("user_id" => $val['change_user_id']));
				if (!empty($user))
				{
					$chistory_changes_list[$key]['user_name'] = (string) $user['user_name'];
				}
	
				//название объекта изменения
				$chistory_changes_list[$key]['object_name'] = $this->change_objects[$val['change_object']]['name'];
				
				//название действия
				$chistory_changes_list[$key]['operation'] = $this->change_types[$val['change_type']];
				
				$chistory_changes_list[$key]['object_url'] = My_Url_Helper::replace_change_type_url($this->change_objects[$val['change_object']]['admin_url'], $val['object_id']);
				
			}
				
			$total_changes = $this->CI->model_common->select_count("change_histories", $this->get_history_changes_where($hash));
				
			return array("changes" => $chistory_changes_list, "total_changes" => $total_changes);
		}
	
		return null;
	
	}
	
	
	private function get_history_changes_where($hash)
	{
		$array = Common_Helper::hash_to_array($hash);
		
		
		$where = null;
		if (count($array) > 0)
		{
			if (!empty($array['date_start']))
			{
				$where[] = "change_date >= '" . date("Y-m-d H:i:s",strtotime($array['date_start'])) . "'";
			}
				
			if (!empty($array['date_end']))
			{
				$where[] = "change_date <= '" . date("Y-m-d H:i:s",strtotime($array['date_end'])) . "'";
			}
		
			if (!empty($array['change_user']))
			{
				$where[] = "change_user_id = '" . intval($array['change_user']) . "'";
			}
		
			if (!empty($array['change_operation_type']))
			{
				$where[] = "change_type = '" . (string)$array['change_operation_type'] . "'";
			}
		
			if (!empty($array['change_object']))
			{
				$where[] = "change_object = '" . (string)$array['change_object'] . "'";
			}
		
			if (count($where) > 0)
			{
				$where = implode(" and ", $where);
			}
				
		}
		
		return (string) $where;
	}
	
	private function get_history_changes_order($hash)
	{
		$array = Common_Helper::hash_to_array($hash);
		if (count($array) > 0)
		{
			if (!empty($array['sort_field']) and !empty($array['sort_type']))
			{
				return (string) $array['sort_field'] . " " . $array['sort_type'];
			}
		}
		return "change_date desc";
	}
	
	
}

?>