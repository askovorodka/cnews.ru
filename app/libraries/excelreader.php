<?php 

/**
 * ����� ��������� excel ������� *.xlsx � ��� �� ��������� excel �� ����.
 */
set_include_path(dirname(__FILE__) . '/php_excel_classes/');
include 'PHPExcel/IOFactory.php';

class ExcelReader
{
	
	private $sheets = 0;
	private $data = array();
	private $excel_object = null;
	
	function __construct()
	{
		
	}
	
	public function readfile($filename=null)
	{
		if (empty($filename) or !is_file($filename))
		{
			show_error("�� ������ ���� excel");
		}
		
		$this->excel_object = PHPExcel_IOFactory::load($filename);
		
		$this->data = $this->excel_object->getActiveSheet()->toArray(null,true,true,true);
		
		if (!empty($this->data))
		{
			$this->readexcel();
		}
		
	}
	
	
	private function readexcel()
	{
		print_r($this->data);
	}
	
}

?>