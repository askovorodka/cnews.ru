<?php 

class Tables_Views
{
	
	private $CI = null;
	private $conditions = null;
	private $hash = null;
	public $page = null;
	public $limit = PER_PAGE_TABLES;
	
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model(array('model_common','model_tables'));
		$this->CI->load->library(array('pagination'));
	}
	
	
	/**
	 * Список таблицы для админки
	 * @param String $hash
	 * @param Int $page
	 * @return Array
	 * @author ashmits by 21.01.2013 12:05
	 */
	function get_tables_list($hash = null, $page = 0)
	{
		$this->hash = (string)$hash;
		$this->page = (int)$page;
		
		$items = $this->CI->model_tables->get_tables_list_admin($this);
		
		if (count($items))
		{
			//пейджинг
			$p_config = $this->CI->config->item('paging');
			$p_config['base_url'] = DOMAIN."/admin/tables/tables_list/{$hash}/";
			$p_config['total_rows'] = $this->CI->model_common->select_count("tables", $this->get_tables_list_conditions());
			$p_config['per_page'] = PER_PAGE_TABLES;
			$p_config['uri_segment'] = 5;
			
			$this->CI->pagination->initialize($p_config);
			$paging = $this->CI->pagination->create_links();
			$this->CI->smarty->assign('paging', $paging);
			
		}
		
		return $items;
		
	}
	
	
	public function get_tables_list_conditions()
	{
		
		$this->conditions = array();
		
		if (!empty($this->hash))
		{
			$array = Common_Helper::hash_to_array($this->hash);
			if (!empty($array['date_start']))
			{
				$this->conditions[] = "date >='" . date("Y-m-d H:i:s",strtotime($array['date_start'])) . "'";
			}
			if (!empty($array['date_end']))
			{
				$this->conditions[] = "date <='" . date("Y-m-d H:i:s",strtotime($array['date_end'])) . "'";
			}
			if (!empty($array['change_user']))
			{
				$this->conditions[] = "users_user_id = '" . intval($array['change_user']) . "'";
			}
			if (!empty($array["table_status"]))
			{
				$this->conditions[] = sprintf("table_status = '%d'", intval($array['table_status']));
			}
		}
		
		if (count($this->conditions) > 0)
		{
			return implode(" and ", $this->conditions);
		}
		return null;
	}
	
	
	public function get_tables_list_order()
	{
		$array = Common_Helper::hash_to_array($this->hash);
		if (!empty($array) and !empty($array['sort_field']) and !empty($array['sort_type']))
			return $array['sort_field'] . " " . $array['sort_type'];
		
		return "date desc";
		
	}
	
	
	public function get_table_by_id($table_id = null, $status = null)
	{
		
		$where = array("table_id" => intval($table_id));
		
		if (!empty($status))
		{
			$where = array_merge($where, array("table_status" => intval($status)));
		}
		
		$table = $this->CI->model_common->select_one("tables", $where);
		
		if (!empty($table))
			return $table;
		else
			return null;
		
	}
	
	
}

?>