<?php 

/**
 * Генерация пустой таблицы
 * @author ashmits
 *
 */
final class Generate_Table extends Reviews_Collector
{
	
	private $rows, $cols, $validate, $template = null;
	
	public function __construct()
	{
		parent::__construct();
		$this->CI->load->library('table');
		$this->template = $this->CI->config->item('excel_to_table');
		//инициализируем шаблон html таблиц
		$this->CI->table->set_template($this->template);
		
	}
	
	/**
	 * Создание пустой таблицы
	 */
	function empty_table()
	{
		$data = $this->generate_empty_table();
		
		if (!empty($data))
		{
			for ($row=1; $row <= $data['rows']; $row++)
			{
				if ($row == 1)
				{
					$this->CI->table->set_heading(array_fill(1, $data['cols'], "Заголовок"));
				}
				else
				{
					$this->CI->table->add_row(array_fill(1, $data['cols'], " "));
				}
			}
			
			return $this->CI->table->generate();
			
		}
		
	}
	
}

?>