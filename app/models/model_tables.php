<?php 
/**
 * Модель таблиц
 * @author ashmits by 23.01.2013 15:00
 *
 */
class Model_Tables extends Model
{
	
	function __construct()
	{
		parent::Model();
	}
	
	/**
	 * Список таблиц для админки
	 * @author ashmits by 23.01.2013 15:24
	 * @param unknown_type $where
	 * @param unknown_type $sort_order
	 * @param unknown_type $limit_start
	 * @param unknown_type $limit_end
	 * @return NULL
	 */
	function get_tables_list($where = null, $sort_order = null, $limit_start = 0, $limit_end = PER_PAGE_TABLES)
	{
		$this->db->select('*')
		->from('tables')
		->join('users', 'tables.users_user_id = users.user_id', 'left');
		if (!empty($where))
		{
			$this->db->where((string)$where);
		}
		
		if (!empty($sort_order))
		{
			$this->db->orderby((string)$sort_order);
		}
		
		$this->db->limit(intval($limit_end), intval($limit_start));
		
		$result = $this->db->get();
		
		
		if ($result and $result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return null;
		}
		
	}
	
	public function get_tables_list_admin(Tables_Views $views)
	{

		$where = $views->get_tables_list_conditions();
		$order = $views->get_tables_list_order();
		
		$this->db->select('*')
		->from('tables')
		->join('users', 'tables.users_user_id = users.user_id', 'left');
		
		if ($where)
			$this->db->where( $where );
	
		$this->db->orderby( $order );
		
		$this->db->limit($views->limit, $views->page);
		
		$result = $this->db->get();
		
		//echo $this->db->last_query();
		
		if ($result and $result->num_rows() > 0)
		{
			return $result->result_array();
		}
		else
		{
			return null;
		}
		
		
	}
	
}

?>