<?php
/**
 * Дополнительный модуль к CodeIgniter.
 * Добавляет в стандарнтные классы фреймворка функциональность Smarty.
*/

class CI_Smarty extends Smarty {
	function CI_Smarty()
	{
		$this->Smarty();
		$config =& get_config();

		//Основные дерриктории шаблона
		$this->template_dir = $config["smarty_template_dir"];
		$this->compile_dir =  $config["smarty_compile_dir"]; 
		$this->config_dir =  $config["smarty_config_dir"]; 
		$this->cache_dir =  $config["smarty_config_dir"]; 
		//$this->debugging = true; //Отладчик
		//$this->error_reporting = 0;

	}
} 
?>