<?php
/**
 * �������������� ������ � CodeIgniter.
 * ��������� � ������������ ������ ���������� ���������������� Smarty.
*/

class CI_Smarty extends Smarty {
	function CI_Smarty()
	{
		$this->Smarty();
		$config =& get_config();

		//�������� ����������� �������
		$this->template_dir = $config["smarty_template_dir"];
		$this->compile_dir =  $config["smarty_compile_dir"]; 
		$this->config_dir =  $config["smarty_config_dir"]; 
		$this->cache_dir =  $config["smarty_config_dir"]; 
		//$this->debugging = true; //��������
		//$this->error_reporting = 0;

	}
} 
?>