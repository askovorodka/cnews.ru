<?php

/**
 * Класс парсинга Excel файлов в таблицы html
 * @author ashmits by 12.12.2012 12:51
 *
 */
require_once APPPATH.'/libraries/spreadsheet_excel_reader.php';

class ExcelToTable extends Spreadsheet_Excel_Reader
{
	
	private $file=null;
	private $tables = array();
	private $CI = null;
	private $encoding = 'CP1251';
	private $template = null;
	
	/**
	 * Конструктор
	 * @param path_to_file $file
	 */
	function __construct($file=null)
	{
		try{
			//конструктор парент класса
			parent::Spreadsheet_Excel_Reader($file);
			$this->CI =& get_instance();
			//грузим либу для генерации табличек html
			$this->CI->load->library(array('table'));
			$this->template = $this->CI->config->item('excel_to_table');
			//инициализируем шаблон html таблиц
			$this->CI->table->set_template($this->template);
		}
		catch(Exception $error){
			show_error($error->getMessage());
		}
	}

	function generate_empty_table($rows=0, $cols=0)
	{
		$this->CI->table->set_caption('Новая таблица');
	}
	
	/**
	 * Чтение файла
	 * @param string $file
	 * @param unknown_type $encoding
	 */
	function read_file($file, $encoding=null)
	{
		
		if (empty($file) or !file_exists($file))
		{
			show_error("Не передан файл для чтения");
		}
		
		$this->file = (string)$file;
		
		if (!empty($encoding))
		{
			$this->encoding = (string)$encoding;
		}
		
		try{
			$this->run();
		}
		catch (Exception $error)
		{
			show_error($error->getMessage());
		}
		
	}
	
	/**
	 * Возвращает таблицы html
	 * @return multitype:html table
	 */
	function get_tables()
	{
		
		return $this->tables;
	}
	
	/**
	 * Обработка файла
	 */
	private function run()
	{
		$this->setOutputEncoding($this->encoding);
		$this->read($this->file);
		
		if ($this->sheets > 0)
		{
			for ($sheet=0; $sheet < count($this->sheets); $sheet++)
			{
				$this->CI->table->clear();
				$check=false;
				for ($row=1; $row <= $this->rowcount($sheet); $row++)
				{
					
					$item=null;
					for ($col=1; $col <= $this->colcount($sheet); $col++)
					{
						$item[] = $this->val($row, $col, $sheet);
						$check = true;
					}
					
					if ($row == 1)
					{
						$this->CI->table->set_heading($item);
					}
					else
					{
						$this->CI->table->add_row($item);
					}
				}
				if ($check)
				{
					$this->tables[] = trim($this->CI->table->generate());
				}
				
			}
			
		}
		
	}
	
}

?>