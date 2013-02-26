<?php
/**
 * Сборщик данных для таблиц
 * @author ashmits
 *
 */
class TableCollector extends Validate
{
	
	private $data = array();
	private $CI = null;
	
	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	function editadd($table = null)
	{
		
		$this->description = $this->set_text($this->description);
		$this->table_status = $this->set_int($this->table_status);
		
		if ($this->structure)
		{
			$this->structure = $this->set_text(Common_Helper::set_structure_table($this->structure));
		}
		
		if (empty($table))
		{
			$this->users_user_id = $this->set_active_user();
			$this->date = $this->set_date();
		}
		else
		{
			$this->users_user_id = $this->set_int($table['users_user_id'], true, "Не определен пользователь таблицы");
		}
		
		$this->source = $this->set_text(nl2br($this->source));
		$this->rating = $this->set_text(nl2br($this->rating));
		
		return $this->data;
	}
	
	
	function __set($name, $value = null)
	{
		$this->data[$name] = $value;
	}
	
	function __get($key)
	{
		return $this->CI->input->post($key);
	}
	
}

?>